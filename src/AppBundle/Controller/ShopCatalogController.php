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

        $query = $this->em->getRepository('AppBundle:Category')->queryFindAll();

        // null == infinitive cache
        // $query->useResultCache(true, null, 'findAllAccessory');

        // Not need translation fallback and using inner join insted of left join
        $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
        $query->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $request->getLocale()); // Memcached or Apc
        $query->setHint(TranslatableListener::HINT_INNER_JOIN, true);

        $categories = $query->getResult();

        dump($categories);

        return $this->render('AppBundle:ShopCatalog:list.html.twig', array('category' => $category));
    }
}
