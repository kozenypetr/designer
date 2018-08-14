<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;

class ShopCatalogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);*/

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $products   = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('AppBundle:ShopCatalog:index.html.twig', array('products' => $products));
    }


    /**
     * @Route("/kategorie/{slug}", name="shop_catalog_list")
     */
    public function listAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $category = $this->em->getRepository('AppBundle:Category')->findOneBySlug($slug);

        if (!$category)
        {
            throw $this->createNotFoundException('Kategorie neexistuje');
        }

        return $this->render('AppBundle:ShopCatalog:list.html.twig', array('category' => $category));
    }

    /**
     * @Route("/produkt/{slug}", name="shop_catalog_detail")
     */
    public function detailAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $product = $this->em->getRepository('AppBundle:Product')->findOneBySlug($slug);

        if (!$product)
        {
            throw $this->createNotFoundException('Produkt neexistuje');
        }

        return $this->render('AppBundle:ShopCatalog:detail.html.twig', array('product' => $product));
    }


    public function categoriesAction()
    {
        $this->em = $this->getDoctrine()->getManager();

        $categories = $this->em->getRepository('AppBundle:Category')->findAll();

        return $this->render('AppBundle:ShopCatalog:categories.html.twig', array('categories' => $categories));
    }

}
