<?php
/**
 * Created by PhpStorm.
 * User: petr
 * Date: 27.10.18
 * Time: 14:51
 */

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use AppBundle\Service\Mailer;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderStatus;


class OrderManager {



    protected $em = NULL;
    protected $mailer = NULL;
    protected $twig;

    /**
     * @param Session $session
     * @param EntityManager $em
     * @param TokenStorage $tokenStorage
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $twig
     * @param KernelInterface
     *
     */
    public function __construct(EntityManagerInterface $em, Mailer $mailer, EngineInterface $twig) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }


    public function getOriginalData($order)
    {
        return $this->em->getUnitOfWork()->getOriginalEntityData($order);
    }

    /**
     * Odeslani emailu zakaznikovi se zmenou stavu objednavky
     * @param $order Order
     * @param $status OrderStatus
     * @param $message string
     */
    public function sendUpdateStatusMail($order, $status, $message = null, $forceSubject = null)
    {
        // jestli je definovana sablona u stavu objednavky, tak odesleme mail
        if ($status->getEmail())
        {
            $data = ['order' => $order, 'message' => $message];

            $subject = 'Změna stavu objednávky č. ' . $order->getId();

            if ($forceSubject)
            {
                $subject = $forceSubject;
            }

            $template = 'AppBundle:Email/order/status:' . $status->getEmail();

            $this->mailer->send($subject, $subject, $order->getEmail(), $data, $template);
        }
    }


}