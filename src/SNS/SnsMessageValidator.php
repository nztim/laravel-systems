<?php declare(strict_types=1);

namespace NZTim\SNS;

use Illuminate\Cache\Repository;
use NZTim\SimpleHttp\Http;

class SnsMessageValidator
{
    private Http $http;
    private Repository $cache;

    public function __construct(Http $http, Repository $cache)
    {
        $this->http = $http;
        $this->cache = $cache;
    }

    public function validate(array $data): Result
    {
        // Check the URL
        $url = $data['SigningCertURL'] ?? '';
        $validateResult = $this->validateUrl($url);
        if (!$validateResult->success) {
            return $validateResult;
        }
        // Get the certificate and cache for a while
        $certificate = $this->cache->remember(md5('snscert-' . $url), now()->addDay(), function () use ($url) {
            return $this->http->get($url)->body();
        });
        // Get public key
        $key = openssl_get_publickey($certificate);
        if (!$key) {
            return Result::createFailed('Unable to get the public key from the certificate');
        }
        // Check the signature
        if (($data['SignatureVersion'] ?? '') !== '1') {
            return Result::createFailed('Invalid SignatureVersion: ' . $data['SignatureVersion']);
        }
        $content = $this->stringToSign($data);
        $signature = base64_decode($data['Signature'] ?? '');
        if (openssl_verify($content, $signature, $key, OPENSSL_ALGO_SHA1) !== 1) {
            return Result::createFailed('Signature did not verify');
        };
        return Result::createSuccess();
    }

    private function validateUrl($url): Result
    {
        $parsed = parse_url($url);
        if (($parsed['scheme'] ?? '') !== 'https') {
            return Result::createFailed('Invalid certificate scheme: ' . $url);
        }
        if (!str_ends_with($url, '.pem')) {
            return Result::createFailed('Invalid certificate URL filename: ' . $url);
        }
        if (!preg_match('/^sns\.[a-zA-Z0-9\-]{3,}\.amazonaws\.com(\.cn)?$/', $parsed['host'])) {
            return Result::createFailed('Invalid hostname: ' . $url);
        }
        return Result::createSuccess();
    }

    public function stringToSign(array $data): string
    {
        static $signableKeys = [
            'Message',
            'MessageId',
            'Subject',
            'SubscribeURL',
            'Timestamp',
            'Token',
            'TopicArn',
            'Type',
        ];
        $stringToSign = '';
        foreach ($signableKeys as $key) {
            if (isset($data[$key])) {
                $stringToSign .= "{$key}\n{$data[$key]}\n";
            }
        }
        return $stringToSign;
    }
}
