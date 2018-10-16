<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Intervention\Image\ImageManagerStatic as ImageEditor;
use AppBundle\Entity\Image;

use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ImageController extends Controller
{
    /**
     * @Route("/image/cache/{id}/{setting}", name="image_cache")
     */
    public function imageCacheAction(Request $request, Image $image, $setting)
    {
        $size = array();
        list($width, $height, $crop) = explode('x', $setting);
        $extension = pathinfo($image->getFilename(), PATHINFO_EXTENSION);

        $filename  = "{$image->getId()}_{$width}_{$height}_{$crop}.{$extension}";

        if (file_exists($this->getCacheDir() . '/' . $filename))
        {
            /*header('Content-Type: image/' . $extension);
            header('Content-Length: ' . filesize($this->getCacheDir() . '/' . $filename));
            readfile($this->getCacheDir() . '/' . $filename);
            exit;*/
            $response = new BinaryFileResponse($this->getCacheDir() . '/' . $filename);
            return $response;
        }

        $file = $this->getDataDir() . '/' . $image->getFilename();
        $im = ImageEditor::make($file);

        if ($crop)
        {
            $im->fit($width, $height);
        }
        else {
            $im->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $im->save($this->getCacheDir() . '/' . $filename);

        header('Content-type: image/jpg');

        echo $im->response();
        exit;
    }

    protected function getDataDir()
    {
        return realpath($this->get('kernel')->getRootDir() . '/../web/data/shop/product') . '/';
    }

    protected function getCacheDir()
    {
        return realpath($this->get('kernel')->getRootDir() . '/../web/data/shop/cache') . '/';
    }
}
