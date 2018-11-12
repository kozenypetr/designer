<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use AppBundle\Entity\Order;



class GopayController extends Controller
{
    /**
     * @Route("/opakovana-platba-objednavky/{hash}", name="shop_gopay_repayment")
     */
    public function orderRepaymentAction(Request $request, $hash)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var Order $order */
        $order = $em->getRepository('AppBundle:Order')->findOneByHash($hash);

        if (!$order)
        {
            return $this->createNotFoundException();
        }

        $this->get('session')->set('finished_order_id', $order->getId());

        $this->get('shop.gopay')->checkStatus($order->getGopayId());

        if (in_array($order->getGopayState(), array('PAID', 'REFUNDED', 'PARTIALLY_REFUNDED', 'PAYMENT_METHOD_CHOSEN')))
        {
            return $this->redirectToRoute('shop_order_finish_confirm');
        }

        if ($order->getGopayState() == 'CREATED')
        {
            return $this->redirectToRoute('shop_gopay_payment');
        }

        $this->get('shop.gopay')->createPayment($order);

        return $this->redirectToRoute('shop_gopay_payment');
    }



    /**
     * @Route("/platba-objednavky", name="shop_gopay_payment")
     */
    public function orderPaymentAction(Request $request)
    {
        $orderId = $this->get('session')->get('finished_order_id');

        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('AppBundle:Order')->find($orderId);

        if (!$order)
        {
            return $this->createNotFoundException();
        }

        if ($order->getGopayState() != 'CREATED')
        {
            $this->redirectToRoute('shop_order_finish_confirm');
        }

        return $this->render('AppBundle:Gopay:payment.html.twig',
            array(
                'order' => $order
            )
        );
    }


    /**
     * @Route("/gopay/return", name="shop_gopay_return")
     */
    public function gopayReturnAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $gopayId = $request->get('id');

        $order = $em->getRepository('AppBundle:Order')->findOneByGopayId($gopayId);

        if (!$order)
        {
            return $this->createNotFoundException();
        }

        $this->get('shop.gopay')->checkStatus($gopayId);

        if ($order->getGopayState() == 'CREATED')
        {
            return $this->redirectToRoute('shop_gopay_payment');
        }

        // presmerovani na dokonceni objednavky
        return $this->redirectToRoute('shop_order_finish_confirm');
    }

    /**
     * @Route("/gopay/notify", name="shop_gopay_notify")
     */
    public function gopayReturnNotify(Request $request)
    {
        $gopayId = $request->get('id');

        $this->get('shop.gopay')->checkStatus($gopayId);

        return new Response(
            'OK',
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
    }
}
