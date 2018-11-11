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



class ShopCustomerController extends Controller
{


    protected function getCustomer()
    {
        $customer = $this->getUser(); // $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($customer))
        {
            $customer = null;
        }
        // dump($customer);
        // dump($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));
        return $customer;
    }

    /**
     * @Route("/objednavka/zakaznik", name="shop_customer")
     * @Method({"GET", "POST"})
     */
    public function customerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $cm = $this->get('cart.manager');
        $em = $this->getDoctrine()->getManager();

        $customer = $this->getCustomer();

        // @TODO kontrola obsahu kosiku + nastaveni dopravy a platby
        $valid = true;
        if (!$cm->getCart()->getShipping())
        {
            $valid = false;
            $this->addFlash(
                'danger',
                'Potřebovali bychom vědět, jak Vám máme balíček doručit :)'
            );
        }

        if (!$cm->getCart()->getPayment())
        {
            $valid = false;
            $this->addFlash(
                'danger',
                'Potřebovali bychom znát platební metodu :)'
            );
        }

        // kontrola zasilkovna
        if ($cm->getCart()->getShipping()->getCode() == 'zasilkovna')
        {
            $shippingParameters = $cm->getCart()->getShippingParameters();
            if (!isset($shippingParameters['place']))
            {
                $valid = false;
                $this->addFlash(
                    'danger',
                    'Potřebovali bychom vědět, na jakou pobočku ZÁSILKOVNY máme balíček poslat :) Vyberte prosím níže u dopravy.'
                );
            }
        }


        if (!$valid)
        {
            return $this->redirectToRoute('shop_cart');
        }

        $billingFormOptions = [];
        if ($request->isMethod('POST'))
        {
            $billingData = $request->get('customer_billing');
            if (isset($billingData['is_create_account']) && $billingData['is_create_account'])
            {
                $billingFormOptions['registration'] = true;
            }
        }

        // formular pro fakturacni adresu
        $billingForm = $this->createForm(CustomerBillingType::class, null, $billingFormOptions);
        $billingForm->setData($cm->cart->getBillingData());

        // formular pro dodaci adresu
        $deliveryForm = $this->createForm(CustomerDeliveryType::class, null, array('full_address' => $cm->getCart()->getShipping()->getFullAddress()));
        $deliveryForm->setData($cm->cart->getDeliveryData());

        // formular pro zadani hesla
        $passwordForm = $this->createForm(CustomerPasswordType::class, null, array('csrf_protection' => false));

        // po odeslani formulare provedeme validaci a ulozime data
        if ($request->isMethod('POST'))
        {
            $billingForm->handleRequest($request);

            // validace fakturacni adresy
            if ($billingForm->isValid())
            {
                $valid = true;
                $cm->cart->setBillingData($billingForm->getData());

                // pokud je dodaci adresa jina, tak ji ulozime
                if ($cm->cart->getIsDelivery())
                {
                    $deliveryForm->handleRequest($request);
                    $deliveryForm->isValid()?$cm->cart->setDeliveryData($deliveryForm->getData()):$valid = false;
                }
                else
                {
                    $cm->cart->clearDeliveryData();
                }

                if ($billingForm->get('is_create_account')->getViewData())
                {
                    $passwordForm->handleRequest($request);
                    if ($passwordForm->isValid())
                    {
                        $passwordData = $passwordForm->getData();

                        // vytvorime zakaznika
                        $customer = new Customer();
                        $customer->setFromObject($cm->cart);

                        // vytvorime heslo
                        $password = $passwordEncoder->encodePassword($customer, $passwordData['plainPassword']);
                        $customer->setPassword($password);

                        $cm->cart->setCustomer($customer);

                        $em->persist($customer);
                        $em->flush();

                        // prihlaseni zakaznika


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
                    if ($this->getUser()) {
                        $this->getUser()->setFromObject($cm->cart);
                        $em->flush();
                    }

                    return $this->redirectToRoute('shop_summary');
                }
            }



            /*if ($billingForm->getData()['is_delivery'])
            {
                $deliveryForm->handleRequest($request);
            }*/

            /* @TODO: presmerovani na sumarizaci objednavky */
        }

        //

        return $this->render('AppBundle:ShopCart:customer.html.twig',
                        array('cm' => $cm,
                              'billingForm' => $billingForm->createView(),
                              'deliveryForm' => $deliveryForm->createView(),
                              'passwordForm' => $passwordForm->createView(),
                              'fullDeliveryAddress' => $cm->getCart()->getShipping()->getFullAddress()
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
                'delivery' => $cm->cart->getDeliveryData(true)
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

        if ($cm->finishOrder())
        {
            // presmerovani na platbu
            return $this->redirectToRoute('shop_gopay_payment');
        }

        return $this->redirectToRoute('shop_order_finish_confirm');
    }


    /**
     * @Route("/potvrzeni-dokonceni-objednavky", name="shop_order_finish_confirm")
     */
    public function orderFinishConfirmAction(Request $request)
    {
        $orderId = $this->get('session')->get('finished_order_id');

        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('AppBundle:Order')->find($orderId);

        if (!$order)
        {
            return $this->createNotFoundException();
        }

        return $this->render('AppBundle:ShopCart:finishConfirm.html.twig', ['order' => $order]);
    }


}
