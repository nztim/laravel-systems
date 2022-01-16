<?php declare(strict_types=1);

namespace NZTim\Glide;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

abstract class ImageController extends Controller
{
    public function serve(GlideServer $server, $filename)
    {
        try {
            $image = new GlideImage($filename);
            $params = $this->params(request()->all());
            return $server->getImageResponse($image, $params);
        } catch (Throwable $_) {
            throw new NotFoundHttpException('Image not found');
        }
    }

    // Override as required, or just return $params for all
    protected function params(array $params): array
    {
        $permitted = ['w', 'h', 'fit'];
        return array_intersect_key($params, array_flip($permitted));
    }
}
