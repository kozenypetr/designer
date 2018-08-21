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
     * @Route("/objednavka/zakaznik", name="shop_customer")
     * @Method({"GET", "POST"})
     */
    public function customerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $cm = $this->get('cart.manager');
        $em = $this->getDoctrine()->getManager();

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
            $passwordForm->handleRequest($request);

            // validace fakturacni adresy
            if ($billingForm->isValid())
            {
                $valid = true;
                $cm->cart->setBillingData($billingForm->getData());

                // pokud je dodaci adresa jina, tak ji ulozime
                if ($cm->cart->getIsDelivery())
                {
                    $deliveryForm->isValid()?$cm->cart->setDeliveryData($deliveryForm->getData()):$valid = false;
                }

                if ($billingForm->get('is_create_account')->getViewData())
                {
                    if ($passwordForm->isValid())
                    {
                        $passwordData = $passwordForm->getData();

                        // vytvorime zakaznika
                        $customer = new Customer();
                        $customer->fromCart($cm->cart);

                        // vytvorime heslo
                        $password = $passwordEncoder->encodePassword($customer, $passwordData['plainPassword']);
                        $customer->setPassword($password);

                        $cm->cart->setCustomer($customer);

                        $em->persist($customer);
                        $em->flush();

                        $cm->flush();
                    }
                    else
                    {
                        $valid = false;
                    }
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

    /**
     * @Route("/dokonceni-objednavky", name="shop_order_finish")
     * @Method({"POST"})
     */
    public function orderFinishAction(Request $request)
    {
        $cm = $this->get('cart.manager');

        $cm->finishOrder();

        return $this->redirectToRoute('shop_order_finish_confirm');
    }


    /**
     * @Route("/potvrzeni-dokonceni-objednavky", name="shop_order_finish_confirm")
     */
    public function orderFinishConfirmAction(Request $request)
    {
        return $this->render('AppBundle:ShopCart:finishConfirm.html.twig');
    }


}
