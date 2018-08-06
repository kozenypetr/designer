<?php

namespace AdminBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Product;
use AppBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\HttpFoundation\JsonResponse;

use Intervention\Image\ImageManagerStatic as ImageEditor;

class ProductAdminController extends Controller
{

    /**
     * @Route("/product/sortImages", name="product_admin_sort_image", options={"expose"=true})
     */
    public function sortImagesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sort = $request->get('sort');

        $json = array();
        $json['status'] = 'OK';

        try {
            foreach ($sort as $key => $image) {
                $productId = preg_replace('|[^0-9]|', '', $image);

                $qb = $em->getRepository('AppBundle:Image')->createQueryBuilder('i');

                $q = $qb->update()
                        ->set('i.sort', '?1')
                        ->where('i.id = ?2')
                        ->setParameter(1, $key)
                        ->setParameter(2, $productId)
                        ->getQuery();

                $p = $q->execute();
            }

        }
        catch (\Exception $e)
        {
            $json['status'] = 'ERROR';
            $json['message'] = $e->getMessage();
        }

        $response = new JsonResponse($json);

        return $response;
    }


    /**
     * @Route("/product/rotateImage/{id}", name="product_admin_rotate_image")
     */
    public function rotateImageAction(Request $request, Image $image)
    {
        $file = $this->getDataDir() . '/' . $image->getFilename();
        $im = ImageEditor::make($file);

        $im->rotate(90);
        $im->save($file);

        $json = array();
        $json['url'] = '/data/shop/product/' . $image->getFilename() . '?t=' . time();
        $json['id']  = $image->getId();

        $response = new JsonResponse($json);

        return $response;
    }

    /**
     * @Route("/product/deleteImage/{id}", name="product_admin_delete_image")
     */
    public function deleteImageAction(Request $request, Image $image)
    {
        $json = array();
        $json['id']  = $image->getId();

        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        $response = new JsonResponse($json);

        return $response;
    }


    /**
     * @Route("/product/uploadImage/{id}", name="product_admin_upload_image")
     */
    public function uploadImageAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        /**
         * @var UploadedFile $file
         */
        $file = $request->files->get('file');


        $image = new Image();

        $extension = $file->getClientOriginalExtension();
        $originFilename = $file->getClientOriginalName();

        // $image->setExtension($extension);
        $image->setOriginFilename($originFilename);
        $image->setFilename(md5(date('dmYHis') . $originFilename) . '.' . $extension);

        // ziskame maximalni razeni
        $dql = "SELECT MAX(i.sort) AS max FROM AppBundle\Entity\Image i WHERE i.product = ?1";

        $max = $em->createQuery($dql)
                  ->setParameter(1, $product->getId())
                  ->getSingleScalarResult();

        $image->setSort($max + 1);

        $image->setProduct($product);

        $file->move($this->getDataDir(), $image->getFilename());

        $im = ImageEditor::make($this->getDataDir() . '/' . $image->getFilename());
        $image->setWidth($im->width());
        $image->setHeight($im->height());
        $image->setFilesize(filesize($this->getDataDir() . '/' . $image->getFilename()));
        $image->setExtension($file->getClientOriginalExtension());

        $em->persist($image);
        $em->flush();

        return $this->render('AdminBundle:ProductAdmin:images.html.twig', array('product' => $product));
    }

    /**
     * @Route("/product/imageList/{id}", name="product_admin_image_list")
     */
    public function imagesListAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();

        // $product = $em->getRepository('AppBundle:Product')->find($productId);

        return $this->render('AdminBundle:ProductAdmin:imagesList.html.twig', array('product' => $product));
    }


    protected function getDataDir()
    {
        return realpath($this->get('kernel')->getRootDir() . '/../web/data/shop/product') . '/';
    }
}
