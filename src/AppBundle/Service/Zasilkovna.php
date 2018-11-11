<?php
namespace AppBundle\Service;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use AppBundle\Manager\OrderManager;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Routing\Router;



/**
 * Class CartManager
 *
 * @package AppBundle\Service
 */
class Zasilkovna {

    protected $session = NULL;
    protected $em = NULL;
    protected $kernel = null;

    protected $list = null;

    /**
     * @param Session $session
     * @param EntityManager $em
     *
     */
    public function __construct(Session $session, EntityManagerInterface $em, KernelInterface $kernel) {
        $this->session = $session;
        $this->em = $em;
        $this->kernel = $kernel;


        $json = file_get_contents($this->kernel->getRootDir() . '/../zasilkovna/seznam.json');

        $list = \GuzzleHttp\json_decode($json, true);

        $spots = [];
        foreach ($list['data'] as $item)
        {
            if ($item['country'] == 'cz')
            {
                $spots[$item['id']] = $item;
            }
        }

        $this->list = $spots;
    }


    public function getList()
    {
        return $this->list;
    }


    public function getDetail($id)
    {
        return $this->list[$id];
    }

}