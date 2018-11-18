<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use AppBundle\Entity\Order;



class FeedController extends Controller
{
    /**
     * @Route("/sitemap.xml", name="shop_xml_sitemap")
     */
    public function sitemapAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAllActive();

        $categories = $em->getRepository('AppBundle:Category')->findAllActive();

        $pages = [
            'page_gdpr',
            'page_cookies',
            'page_about_buying',
            'page_contact',
            'page_terms',
            'page_about_us',
            'page_for_companies'
        ];

        $xml = $this->get('twig')->render('AppBundle:Feed:sitemap.html.twig', array('products' => $products, 'categories' => $categories, 'pages' => $pages));

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');

        return $response;
    }


    /**
     * @Route("/feed/zbozi.xml", name="shop_xml_zbozi")
     */
    public function zboziAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAllActive();

        $xml = $this->get('twig')->render('AppBundle:Feed:zbozi.html.twig', array('products' => $products));

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');

        return $response;
    }

    /**
     * @Route("/feed/heureka.xml", name="shop_xml_heureka")
     */
    public function heurekaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAllActive();

        $xml = $this->get('twig')->render('AppBundle:Feed:heureka.html.twig', array('products' => $products));

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');

        return $response;
    }


    /**
     * @Route("/feed/google.xml", name="shop_xml_google")
     */
    public function googleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAllActive();

        $xml = $this->get('twig')->render('AppBundle:Feed:google.html.twig', array('products' => $products));

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');

        return $response;
    }
}
