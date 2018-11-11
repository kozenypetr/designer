<?php
namespace AppBundle\Service;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\KernelInterface;


/**
 * Class CartManager
 *
 * @package AppBundle\Manager
 */
class Mailer {

    protected $session = NULL;
    protected $em = NULL;
    protected $tokenStorage = NULL;
    protected $mailer = NULL;
    protected $twig = NULL;
    protected $kernel;

    /**
     * @param Session $session
     * @param EntityManager $em
     * @param TokenStorage $tokenStorage
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $twig
     * @param KernelInterface
     *
     */
    public function __construct(Session $session, EntityManagerInterface $em, TokenStorage $tokenStorage, \Swift_Mailer $mailer, EngineInterface $twig, KernelInterface $kernel) {
        $this->session = $session;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->kernel = $kernel;
    }



    public function send($subject, $title, $to, $data, $template)
    {

        $message = (new \Swift_Message($subject))
            ->setFrom('info@gowood.cz', 'GOWOOD.CZ')
            ->setTo($to)
            ->addBcc('info@gowood.cz')
            ->setBody(
                $this->twig->render('AppBundle:Email:base.html.twig',
                    array(
                        'data' => $data,
                        'title' => $title,
                        'template' => $template
                    )
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);


    }


}