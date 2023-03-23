<?php

namespace NZTim\Mailer;

use Assert\Assert;
use Assert\Assertion;
use Illuminate\Contracts\Mail\Mailer as LaravelMailer;
use Illuminate\Events\Dispatcher;
use Illuminate\Mail\Message as LaravelEmail;

class Mailer
{
    private LaravelMailer $laravelMailer;
    private Dispatcher $dispatcher;
    private CssInliner $cssInliner;
    private HtmlConverter $converter;

    public function __construct(LaravelMailer $laravelMailer, Dispatcher $dispatcher, CssInliner $cssInliner, HtmlConverter $converter)
    {
        $this->laravelMailer = $laravelMailer;
        $this->dispatcher = $dispatcher;
        $this->cssInliner = $cssInliner;
        $this->converter = $converter;
    }

    public const ID_HEADER = 'X-Mailer2-ID';

    public function send(AbstractMessage $message): ?MessageSent
    {
        $this->validate($message);
        if (!$this->validRecipient($message->recipient)) {
            return null;
        }
        $data = array_merge($message->data, ['nztmailerSubject' => $message->subject]);
        $html = $this->cssInliner->process(view($message->view)->with($data)->render());
        $text = $this->converter->convert($html);
        $data = array_merge($data, ['nztmailerHtml' => $html, 'nztmailerText' => $text]);
        $message->messageId = time() . '.' . bin2hex(random_bytes(8)) . '@mailer2.example.org';
        $this->laravelMailer->send(['nztmailer::echo-html', 'nztmailer::echo-text'], $data, function ($email) use ($message) {
            /** @var LaravelEmail $email */
            $email->subject($message->subject);
            $email->to($message->recipientOverride ?: $message->recipient);
            if ($message->sender) {
                $email->from($message->sender, $message->senderName);
            }
            if ($message->replyTo) {
                $email->replyTo($message->replyTo);
            }
            if ($message->cc && !$message->recipientOverride) {
                $email->cc($message->cc);
            }
            if ($message->bcc && !$message->recipientOverride) {
                $email->bcc($message->bcc);
            }
            $headers = $email->getHeaders();
            $headers->addTextHeader(Mailer::ID_HEADER, $message->messageId);
        });
        if ($message->recipientOverride) {
            return null;
        }
        $event = new MessageSent([
            'sender'     => $message->sender,
            'senderName' => $message->senderName,
            'replyTo'    => $message->replyTo,
            'recipient'  => $message->recipient,
            'cc'         => $message->cc,
            'bcc'        => $message->bcc,
            'subject'    => $message->subject,
            'html'       => $html,
            'text'       => $text,
            'messageId'  => $message->messageId,
        ]);
        $this->dispatcher->dispatch($event);
        return $event;
    }

    private function validate(AbstractMessage $message): void
    {
        Assertion::email($message->recipient, 'Recipient not an email address');
        Assert::that($message->subject)->string('Subject is not a string')->notEmpty('Subject is empty');
        Assert::that($message->view)->string('View is not a string')->notEmpty('View is empty');
        Assertion::nullOrEmail($message->sender, 'Sender invalid');
        Assertion::nullOrString($message->senderName, 'SenderName invalid');
        Assertion::nullOrEmail($message->replyTo, 'ReplyTo not an email address');
        Assertion::nullOrEmail($message->recipientOverride, 'recipientOverride not an email address');
        Assertion::nullOrEmail($message->cc, 'cc not an email address');
        Assertion::nullOrEmail($message->bcc, 'bcc not an email address');
        Assertion::isArray($message->data, 'Data not an array');
    }

    private function validRecipient(string $recipient): bool
    {
        $domains = [
            '*@example.org',
            '*@example.com',
            '*.invalid',
        ];
        foreach ($domains as $domain) {
            if (str_is($domain, $recipient)) {
                log_warning('laravel', 'Skipped sending email to invalid recipient: ' . $recipient);
                return false;
            }
        }
        return true;
    }
}

/*
 * LaravelMailer::send provides Illuminate\Mailer\Message $email
 * Looks like the main purpose is to wrap the Symfony\Component\Mime\Email $message property
 * and translate the api. Could switch to Symfony mailer direct, only need to sort out SMTP config which should be easy.
 *
 * Can no longer set the message id but the custom header (X-Mailer2-ID) should go all the way to the recipient
 * and also be included in notifications from all kinds of senders.
 */
