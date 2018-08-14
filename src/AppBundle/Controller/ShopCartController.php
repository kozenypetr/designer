<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;

use AppBundle\Entity\Product;

use AppBundle\Form\CustomerBillingType;
use AppBundle\Form\CustomerDeliveryType;
use AppBundle\Form\CustomerPasswordTypeType;

/**
 * @Route("/kosik")
 */
class ShopCartController extends Controller
{

    /**
     * @Route("/", name="shop_cart")
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
     * @Route("/pridat/{id}", requirements={"id" = "\d+"}, name="shop_cart_add")
     * @Method({"POST"})
     */
    public function addAction(Request $request, Product $product)
    {
        $quantity = $request->get('quantity', 1);

        $this->get('cart.manager')->addItem($product, $quantity);

        return $this->redirectToRoute('shop_cart');
    }



    /**
     * @Route("/zakaznik", name="shop_customer")
     * @Method({"GET", "POST"})
     */
    public function customerAction(Request $request)
    {
        $cm = $this->get('cart.manager');

        // formular pro fakturacni adresu
        $billingForm = $this->createForm(CustomerBillingType::class);
        $billingForm->setData($cm->cart->getBillingData());

        // formular pro dodaci adresu
        $deliveryForm = $this->createForm(CustomerDeliveryType::class);
        $deliveryForm->setData($cm->cart->getDeliveryData());

        // formular pro zadani hesla
        $passwordForm = $this->createForm(CustomerPasswordType::class);

        // po odeslani formulare provedeme validaci a ulozime data
        if ($request->isMethod('POST'))
        {
            $billingForm->handleRequest($request);
            $deliveryForm->handleRequest($request);

            if ($billingForm->isValid())
            {
                $valid = true;
                $cm->cart->setBillingData($billingForm->getData());

                if ($cm->cart->getIsDelivery())
                {
                    $deliveryForm->isValid()?$cm->cart->setDeliveryData($deliveryForm->getData()):$valid = false;
                }

                $cm->flush();

                if ($valid)
                {
                    return $this->redirectToRoute('shop_summary');
                }
            }

            /* @TODO: presmerovani na sumarizaci objednavky */
        }

        //

        return $this->render('AppBundle:ShopCart:customer.html.twig',
                        array('cm' => $cm,
                              'billingForm' => $billingForm->createView(),
                              'deliveryForm' => $deliveryForm->createView(),
                              'passwordForm' => $passwordForm->createView(),
                        )
        );
    }

    /**
     * @Route("/prehled-objednavky", name="shop_summary")
     * @Method({"GET"})
     */
    public function summaryAction(Request $request)
    {
        $cm = $this->get('cart.manager');

        return $this->render('AppBundle:ShopCart:summary.html.twig',
            array(
                'cm' => $cm,
                'billing'  => $cm->cart->getBillingData(),
                'delivery' => $cm->cart->getDeliveryData()
            )
        );
    }

}
