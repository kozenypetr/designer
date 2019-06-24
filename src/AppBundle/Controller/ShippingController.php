<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ShippingController extends Controller
{

    public function zasilkovnaAction(Request $request, $cart)
    {
        // $xml = file_get_contents($this->get('kernel')->->getRootDir() . '/zasilkovna/seznam.xml');
        $spots = $this->get('shop.zasilkovna')->getList();

        $selected = null;

        $cm = $this->get('cart.manager');

        if ($cm->cart->getShipping() && $cm->cart->getShipping()->getCode() == 'zasilkovna')
        {
            $selected = $cm->cart->getShippingParameters();
        }

        return $this->render('AppBundle:Shipping:zasilkovna.html.twig', array('cart' => $cart, 'spots' => $spots, 'selected' => $selected));
    }

}
