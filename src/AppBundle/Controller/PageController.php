<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Intervention\Image\ImageManagerStatic as ImageEditor;
use AppBundle\Entity\Image;

use Symfony\Component\HttpFoundation\BinaryFileResponse;


class PageController extends Controller
{
    /**
     * @Route("/kontakt", name="page_contact")
     */
    public function contactAction(Request $request)
    {
        return $this->render('AppBundle:Page:contact.html.twig', array());
    }

    /**
     * @Route("/obchodni-podminky", name="page_terms")
     */
    public function termAndConditionsAction(Request $request)
    {
        return $this->render('AppBundle:Page:terms.html.twig', array());
    }

    /**
     * @Route("/o-nas", name="page_about_us")
     */
    public function aboutUsAction(Request $request)
    {
        return $this->render('AppBundle:Page:about_us.html.twig', array());
    }


    /**
     * @Route("/pro-firmy", name="page_for_companies")
     */
    public function forCompaniesUsAction(Request $request)
    {
        return $this->render('AppBundle:Page:for_companies.html.twig', array());
    }
}
