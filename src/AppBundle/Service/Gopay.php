<?php
namespace AppBundle\Service;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use AppBundle\Manager\OrderManager;

use AppBundle\Entity\Order;
use GoPay\Api as GopayApi;
use GoPay\Definition\TokenScope as GopayTokenScope;
use GoPay\Definition\Language as GopayLanguage;
use GoPay\Definition\Payment\{Currency, BankSwiftCode, PaymentInstrument, PaymentItemType, Recurrence, VatRate};
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Routing\Router;



/**
 * Class CartManager
 *
 * @package AppBundle\Service
 */
class Gopay {

    protected $session = NULL;
    protected $em = NULL;
    protected $requestStack = null;
    protected $router;
    protected $om;

    protected $goid = '8236164431';
    protected $sercureKey = 'S3BDv9PXb4zBb3ZayRDKnVtC';
    protected $clientId = '1480292616';
    protected $clientSecret = 'b85R2VFy';
    protected $production = false;

    /**
     * @param Session $session
     * @param EntityManager $em
     *
     */
    public function __construct(Session $session, EntityManagerInterface $em, RequestStack $requestStack, Router $router, OrderManager $om) {
        $this->session = $session;
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->om = $om;
    }


    public function checkStatus($gopayId)
    {
        $gopay = $this->createInstance();

        /* @var Order $order */
        $order = $this->em->getRepository('AppBundle:Order')->findOneByGopayId($gopayId);

        if ($order)
        {
            $status = $gopay->getStatus($gopayId);

            if ($order->getGopayState() != (string)$status->json['state'])
            {
                $order->setGopayState((string)$status->json['state']);
                if (isset($status->json['sub_state']))
                {
                    $order->setGopaySubstate((string) $status->json['sub_state']);
                }

                $message = '';
                if ($order->getGopayState() == 'PAID')
                {
                    // vytvoreni noveho stavu a odeslani mailu
                    $orderStatus = $this->em->getRepository('AppBundle:OrderStatus')->find(7);
                    $this->om->sendUpdateStatusMail($order, $orderStatus, '', 'Zaplacená objednávka č. ' . $order->getId());

                }
                else
                {
                    $message = (string)$status->json['state'] . ', ' . $order->getGopaySubstateText();
                    $orderStatus = $this->em->getRepository('AppBundle:OrderStatus')->find(6);
                }

                $order->changeStatus($orderStatus, $message);
            }

            $this->em->flush();
        }
    }

    /**
     * Vytvoreni platby
     * @param Order $order
     */
    public function createPayment(Order $order)
    {
        $gopay = $this->createInstance();

        $items = array();
        foreach ($order->getItems() as $item)
        {
            $items[] = ['name' => $item->getName(), 'amount' => $item->getQuantity() * ((float)$item->getPrice() * 100)];
        }

        if ($order->getShippingPrice())
        {
            $items[] = ['name' => 'Doprava', 'amount' => (float)$order->getShippingPrice() * 100];
        }

        if ($order->getPaymentPrice())
        {
            $items[] = ['name' => 'Dobírka', 'amount' => (float)$order->getPaymentPrice() * 100];
        }

        $returnUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . '/gopay/return';
        $notificationUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . '/gopay/notify';


        $deulfaPaymentInstrument = PaymentInstrument::PAYMENT_CARD;

        if ($order->getPaymentCode() == 'gopay-bank-online')
        {
            $deulfaPaymentInstrument = PaymentInstrument::BANK_ACCOUNT;
        }



        $payment = [
            'payer' => [
                'default_payment_instrument' => $deulfaPaymentInstrument,
                'allowed_payment_instruments' => [PaymentInstrument::PAYMENT_CARD, PaymentInstrument::BANK_ACCOUNT],
                'allowed_swifts' => [BankSwiftCode::CESKA_SPORITELNA, BankSwiftCode::KOMERCNI_BANKA, BankSwiftCode::MBANK, BankSwiftCode::RAIFFEISENBANK, BankSwiftCode::FIO_BANKA, BankSwiftCode::CSOB],
                'contact' => [
                    'email' => $order->getEmail(),
                    'phone_number' => $order->getPhone(),
                ],
            ],
            'amount' => $order->getTotal() * 100,
            'currency' => Currency::CZECH_CROWNS,
            'order_number' => $order->getId(),
            'order_description' => 'GOWOOD - platba za objednávku č. ' . $order->getId(),
            'items' => $items,
            'callback' => [
                'return_url' => $returnUrl,
                'notification_url' => $notificationUrl
            ],
            'lang' => GopayLanguage::CZECH, // if lang is not specified, then default lang is used
        ];

        $response = json_decode($gopay->createPayment($payment), true);

        if ($response && !isset($response['errors']))
        {
            $order->setGopayId($response['id']);
            $order->setGopayState($response['state']);
            $order->setGopayGwUrl($response['gw_url']);
            $order->setGopayJsUrl($gopay->urlToEmbedJs());

            // nastavime stav objednavky - ceka na zaplaceni
            $status = $this->em->getRepository('AppBundle:OrderStatus')->find(5);
            $order->changeStatus($status, 'GopayID = ' . $response['id']);

            $result = true;
        }
        else
        {
            $order->setGopayState('ERROR');
            $status = $this->em->getRepository('AppBundle:OrderStatus')->find(8);
            $order->changeStatus($status);

            $result = false;
        }

        $this->em->persist($order);
        $this->em->flush();

        return $result;
    }

    /**
     * Vytvoreni instance pro komunikaci GoPay
     * @return \GoPay\Payments
     */
    protected function createInstance()
    {
        return GopayApi::payments([
            'goid' => $this->goid,
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'isProductionMode' => $this->production,
            'scope' => GopayTokenScope::ALL,
            'language' => GopayLanguage::CZECH,
            'timeout' => 30
        ]);
    }

}