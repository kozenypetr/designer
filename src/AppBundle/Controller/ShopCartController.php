<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;

use AppBundle\Entity\Product;
use AppBundle\Entity\Customer;

use AppBundle\Form\CustomerBillingType;
use AppBundle\Form\CustomerDeliveryType;
use AppBundle\Form\CustomerPasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\JsonResponse;



class ShopCartController extends Controller
{

    /**
     * @Route("/kosik", name="shop_cart")
     * @Method({"POST", "GET"})
     */
    public function cartAction(Request $request)
    {
        $cm = $this->get('cart.manager');

        $em = $this->getDoctrine()->getManager();

        $shippings = $em->getRepository('AppBundle:Shipping')->findActive($request->getLocale());

        $payments  = $em->getRepository('AppBundle:Payment')->findActive($request->getLocale());

        if ($request->isMethod('POST'))
        {
            $shippingId = $request->get('shipping');
            $paymentId  = $request->get('payment');

            $cm->setShipping($shippingId);
            $cm->setPayment($paymentId);
            $cm->flush();

            return $this->redirectToRoute('shop_customer');
        }


        return $this->render('AppBundle:ShopCart:cart.html.twig', array('cart' => $cm->getCart(), 'shippings' => $shippings, 'payments' => $payments));
    }


    /**
     * @Route("/kosik/pridat/{id}", requirements={"id" = "\d+"}, name="shop_cart_add")
     * @Method({"POST"})
     */
    public function addAction(Request $request, Product $product)
    {
        $quantity = $request->get('quantity', 1);

        $attributes = $request->get('attribute');

        $this->get('cart.manager')->addItem($product, $quantity, $attributes);

        return $this->redirectToRoute('shop_cart');
    }

    /**
     * @Route("/cart/update/{do}", name="shop_cart_update")
     * @Method({"GET"})
     */
    public function updateAction(Request $request, $do)
    {
        $this->cm = $this->get('cart.manager');

        call_user_func_array(
            array($this, $do),
            array($request)
        );

        $shippings = $this->cm->getActiveShippingList($request->getLocale());
        $payments  = $this->cm->getActivePaymentList($request->getLocale());

        $json = ['redirect' => null, 'boxes' => []];

        $summary = $this->cm->getSummary();

        if ($summary['count'] == 0)
        {
            $json['redirect'] = $this->generateUrl("shop_cart");
            return new JsonResponse($json);;
        }

        $json['boxes']['box-step1-cart-items'] = $this->get('templating')->render('AppBundle:ShopCart/boxes:step1-items.html.twig', ['cart' => $this->cm->getCart()]);
        $json['boxes']['box-step1-shipping-payment'] = $this->get('templating')->render('AppBundle:ShopCart/boxes:step1-shipping-payment.html.twig', ['cart' => $this->cm->getCart(), 'shippings' => $shippings, 'payments' => $payments]);
        $json['boxes']['box-step1-total'] = $this->get('templating')->render('AppBundle:ShopCart/boxes:step1-total.html.twig', ['cart' => $this->cm->getCart()]);
        $json['boxes']['box-head-cart'] = $this->get('templating')->render('AppBundle:ShopCart/boxes:head-cart.html.twig');

        return new JsonResponse($json);
    }

    protected function updateQuantity(Request $request)
    {
        $this->cm->updateQuantityItem($request->get('id'), $request->get('v'));
    }

    protected function deleteItem(Request $request)
    {
        $this->cm->deleteItem($request->get('id'));
    }

    protected function setPayment(Request $request)
    {
        $paymentId  = $request->get('v');
        $this->cm->setPayment($paymentId);
        $this->cm->flush();
    }

    protected function setShipping(Request $request)
    {
        $shippingId = $request->get('v');
        $this->cm->setShipping($shippingId);
        $this->cm->flush();
    }
}
