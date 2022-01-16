<?php declare(strict_types=1);

namespace NZTim\Glide;

use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;

class GlideImage
{
    public static function newFromUploadedFile(UploadedFile $file): GlideImage
    {
        $hash = md5_file($file->getRealPath());
        $extension = $file->getClientOriginalExtension();
        return new GlideImage($hash . '.' . $extension);
    }

    public static function newFromImageData(string $data, string $extension): GlideImage
    {
        return new GlideImage(md5($data) . '.' . $extension);
    }

    private string $hash;
    private string $extension;

    public function __construct(string $filename) // md5-hash.extension
    {
        $this->hash = pathinfo($filename, PATHINFO_FILENAME);
        $this->extension = str_replace('jpeg', 'jpg', strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
        if (!in_array($this->extension, ['jpg', 'gif', 'png'], true)) {
            throw new \InvalidArgumentException('Invalid extension: ' . $this->extension);
        }
    }

    public function filename($extension = true): string
    {
        $filename = $this->hash;
        if ($extension) {
            $filename .= '.' . $this->extension;
        }
        return $filename;
    }

    public function path($filename = true): string
    {
        $path = substr($this->hash, 0, 2) . '/' . substr($this->hash, 2, 2);
        if ($filename) {
            $path .= '/' . $this->filename();
        }
        return $path;
    }

    // Can pass actual params for Glide or w.h.f shorthand: 250.auto.fit
    public function url($params = null): string
    {
        if (!is_array($params)) {
            $elements = explode('.', strval($params));
            $params = [
                'w'   => $elements[0],
                'h'   => $elements[1] ?? 'auto',
                'fit' => $elements[2] ?? 'max',
            ];
        }
        return route('image.serve', [$this->filename()] + $params);
    }
}
