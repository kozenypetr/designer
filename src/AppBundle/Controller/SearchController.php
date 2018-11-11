<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class SearchController extends Controller
{


    /**
     * @Route("/vyhledavani", name="shop_search")
     */
    public function searchAction(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();

        $query = $request->get('query');

        $products = $this->em->getRepository('AppBundle:Product')->search($query);

        return $this->render('AppBundle:Search:search.html.twig', array('products' => $products, 'query' => $query));
    }

}
