<?php

/**
 *
 */

namespace AppBundle\Manager;


use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use AppBundle\Service\Gopay;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
use AppBundle\Entity\Product;
use AppBundle\Entity\Customer;
use AppBundle\Entity\OrderStatus;


/**
 * Class CartManager
 *
 * @package AppBundle\Manager
 */
class CartManager {

    protected $session    = null;
    protected $em         = null;
    protected $tokenStorage = null;
    protected $mailer     = null;
    protected $twig  = null;
    protected $kernel;
    protected $gopay = null;

    /**
     * @var Cart
     */
    public $cart = null;


    /**
     * @param Session $session
     * @param EntityManager $em
     * @param TokenStorage $tokenStorage
     */
    public function __construct(Session $session, EntityManagerInterface $em, TokenStorage $tokenStorage, \Swift_Mailer $mailer,  EngineInterface $twig, KernelInterface $kernel, Gopay $gopay)
    {
        $this->session = $session;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->kernel = $kernel;
        $this->gopay = $gopay;

        $this->cart = $this->getCart();
    }

    /**
     * Aktualizace kosiku pro zakaznika
     * @param $user Customer
     */
    public function updateCart($customer)
    {
        $cart = $this->em->getRepository('AppBundle:Cart')->findOneBy(['customer' => $customer]);
        $currentCart = $this->em->getRepository('AppBundle:Cart')->find((int)$this->session->get('cart'));

        $joinQuantity = 0;

        if ($cart)
        {
            $joinQuantity = $cart->getItems()->count();

            $cart->setFromObject($customer);

            if ($currentCart) {
                $this->join($cart, $currentCart);
                $this->em->remove($currentCart);
            }

            $this->session->set('cart', $cart->getId());
        }
        else
        {
            $currentCart = $this->loadCart();
            $currentCart->setFromObject($customer);
            $currentCart->setCustomer($customer);
            $this->em->persist($currentCart);
        }

        $this->em->flush();

        return $joinQuantity;
    }

    /**
     * Spojeni kosiku - nacteny od uzivatele a aktualni
     * @param $cart
     * @param $joinCart
     */
    public function join(Cart $first, Cart $second)
    {
        if ($second)
        {
            if ($second->getItems()->count())
            {
                foreach ($second->getItems() as $item)
                {
                   // zkusime najit polozku podle produktu a hash atributu
                   $query = ['cart' => $first, 'product' => $item->getProduct(), 'attributesHash' => $item->getAttributesHash()];
                   $orderItem  = $this->em->getRepository('AppBundle:CartItem')->findOneBy($query);

                   if ($orderItem)
                   {
                       $orderItem->addQuantity($item->getQuantity());
                   }
                   else
                   {
                       $orderItem = clone $item;
                       $orderItem->setCart($first);
                       $first->addItem($orderItem);
                   }

                   $this->em->persist($orderItem);
                }

                if ($second->getShipping())
                {
                    $first->setShipping($second->getShipping());
                    $first->setShippingParameters($second->getShippingParameters());
                }

                if ($second->getPayment());
                {
                    $first->setPayment($second->getPayment());
                    $first->setPaymentParameters($second->getPaymentParameters());
                }

                $this->em->persist($first);
                $this->em->flush();
            }
        }
    }

    public function finishOrder()
    {
        $order = new Order();
        $order->setBillingData($this->cart->getBillingData());
        $order->setDeliveryData($this->cart->getDeliveryData(true));

        $order->setSubtotal($this->cart->getSubtotal());
        $order->setTax($this->cart->getTax());
        $order->setTotal($this->cart->getTotal());

        $order->setShipping($this->cart->getShipping());
        $order->setShippingName($this->cart->getShipping()->getName());
        $order->setShippingCode($this->cart->getShipping()->getCode());
        $order->setShippingPrice($this->cart->getShippingPrice());

        $order->setPayment($this->cart->getPayment());
        $order->setPaymentName($this->cart->getPayment()->getName());
        $order->setPaymentCode($this->cart->getPayment()->getCode());

        // stav objednavky
        $status = $this->em->getRepository('AppBundle:OrderStatus')->find(1);
        $order->setStatus($status);

        if ($this->cart->getCustomer())
        {
            $order->setCustomer($this->cart->getCustomer());
        }

        $this->em->persist($order);

        foreach ($this->cart->getItems() as $item)
        {
            $orderItem = new OrderItem();
            $product   = $item->getProduct();
            $orderItem->setName($product->getName());
            $orderItem->setModel($product->getModel());
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setPrice($item->getPrice());
            $orderItem->setOrder($order);
            $orderItem->setAttributes($item->getAttributes());

            $order->addItem($orderItem);

            $this->em->persist($orderItem);
        }

        $this->em->flush();

        $redirectToPayment = false;

        if ($this->cart->getPayment()->isGopay())
        {
            $this->gopay->createPayment($order);
            $redirectToPayment = true;
        }

        $message = (new \Swift_Message('Přijali jsme vaši objednávku č. ' . $order->getId()))
            ->setFrom('info@kozenypetr.cz', 'GOWOOD.CZ')
            ->setTo($order->getEmail())
            ->addBcc('info@gowood.cz')
            ->setBody(
                $this->twig->render('AppBundle:ShopCart:finishOrderEmail.html.twig',
                    array(
                        'order' => $order,
                    )
                ),
                'text/html'
            )
            ->attach(\Swift_Attachment::fromPath(realpath($this->kernel->getRootDir() . '/../web/assets/') . '/obchodni-podminky.pdf'))
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;

        $this->mailer->send($message);

        $this->em->remove($this->cart);
        $this->session->set('cart', null);
        $this->session->set('finished_order_id', $order->getId());

        $this->em->flush();

        return $redirectToPayment;
    }

    public function getCart()
    {
        if ($this->session->get('cart', null))
        {
            $cart = $this->getCartById($this->session->get('cart', null));
            if ($cart)
            {   /*
                if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
                {
                    $cart->setUser($this->securityContext->getToken()->getUser());
                    $this->em->persist($cart);
                    $this->em->flush();
                }
                */
                return $cart;
            }
        }

        $cart = new Cart();
        if ($this->tokenStorage->getToken())
        {
            if ($this->tokenStorage->getToken()->getUser() instanceof Customer) {
                $cart->setCustomer($this->tokenStorage->getToken()->getUser());
                $cart->setFromObject($this->tokenStorage->getToken()->getUser());
            }
        }
        /*if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
            $cart->setUser($this->securityContext->getToken()->getUser());*/
        return $cart;
    }

    public function flush()
    {
        $this->em->persist($this->cart);
        $this->em->flush();
    }

    public function setShipping($shippingId)
    {
        $this->cart->setShipping($this->em->getRepository('AppBundle:Shipping')->find((int)$shippingId));
    }


    public function setPayment($paymentId)
    {
        $this->cart->setPayment($this->em->getRepository('AppBundle:Payment')->find((int)$paymentId));
    }

    public function getSummary()
    {
        $summary = [];
        $summary['count'] = $this->cart->getItems()->count();
        $summary['total'] = $this->cart->getTotalProducts();

        return $summary;
    }

    /**
     * @param Product
     * @param int $quantity
     * @param array $attributes
     * @return bool
     */
    public function addItem($product, $quantity = 1, $attributes = null, $files = null)
    {
        $cart = $this->loadCart();

        $cartItem = $this->findCartItem($cart, $product, $attributes);
        if ($cartItem)
        {
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
            $this->em->persist($cartItem);
            $this->em->flush();
            return true;
        }

        $cartItem = new CartItem();
        $cartItem->setCart($cart);
        $cartItem->setProduct($product);
        $cartItem->setQuantity($quantity);
        $cartItem->setPrice($product->getPrice());

        $productAttributes = array();
        foreach ($product->getAttributes() as $attribute)
        {
            $productAttributes[$attribute->getId()] = $attribute;
        }

        $attributesHash = '';
        if (($attributes || isset($files['attribute'])) && $productAttributes)
        {
            $attributesSaveData = [];
            foreach ($attributes as $id => $value)
            {
                if (isset($productAttributes[$id])) {

                    $attributesSaveData[$id] = [
                        'name' => $productAttributes[$id]->getName(),
                        'value' => $value,
                        'type' => (substr($productAttributes[$id]->getType(), strrpos($productAttributes[$id]->getType(), '\\') + 1)),
                    ];
                }
            }

            if (isset($files['attribute'])) {
                foreach ($files['attribute'] as $id => $file) {
                    if (isset($productAttributes[$id])) {

                        $uploadDir = realpath($this->kernel->getRootDir() . '/../web/data/shop/attributes/');

                        $file = $files['attribute'][$id];

                        $filename = md5(date('his') . $id) . '.' . $file->guessExtension();

                        $file->move($uploadDir, $filename);

                        $attributesSaveData[$id] = [
                            'name' => $productAttributes[$id]->getName(),
                            'value' => $file->getClientOriginalName(),
                            'type' => (substr($productAttributes[$id]->getType(), strrpos($productAttributes[$id]->getType(), '\\') + 1)),
                            'file' => '/data/shop/attributes/' . $filename
                        ];
                    }
                }
            }

            if ($attributesSaveData) {
                $attributesHash = md5(serialize($attributes));
                $cartItem->setAttributesHash($attributesHash);
                $cartItem->setAttributes($attributesSaveData);
            }
        }

        $cart->addItem($cartItem);

        $this->em->persist($cartItem);
        $this->em->persist($cart);
        $this->em->flush();

        return true;
    }

    public function getActiveShippingList($locale)
    {
        return $this->em->getRepository('AppBundle:Shipping')->findActive($locale);
    }

    public function getActivePaymentList($locale)
    {
        return $this->em->getRepository('AppBundle:Payment')->findActive($locale);
    }

    public function updateQuantityItem($id, $quantity)
    {
        $cartItem = $this->em->getRepository('AppBundle:CartItem')->find($id);
        $cartItem->setQuantity($quantity);

        $this->em->persist($cartItem);
        $this->em->flush();
    }

    public function deleteItem($id)
    {
        $cartItem = $this->em->getRepository('AppBundle:CartItem')->find($id);

        $this->em->remove($cartItem);
        $this->em->flush();
    }

    private function findCartItem($cart, $product, $attributes)
    {
        $attributesHash = null;
        if ($attributes)
        {
            $attributesHash = md5(serialize($attributes));
        }

        $cartItem = $this->em->getRepository('AppBundle:CartItem')->findOneBy(array('cart' => $cart, 'product' => $product, 'attributesHash' => $attributesHash));

        return $cartItem;
    }

    /**
     * @return \AppBundle\Entity\Cart
     */
    public function loadCart()
    {
        if ($this->session->get('cart', null))
        {
            $cart = $this->getCartById($this->session->get('cart', null));
            if ($cart)
            {
                return $cart;
            }
        }

        $cart = new Cart();
        if ($this->tokenStorage->getToken()->getUser() instanceof Customer) {
            $cart->setCustomer($this->tokenStorage->getToken()->getUser());
            $cart->setFromObject($this->tokenStorage->getToken()->getUser());
        }

        $this->em->persist($cart);
        $this->em->flush();
        $this->session->set('cart', $cart->getId());

        return $cart;
    }


    /**
     * @param  $cartId
     * @return Cart
     */
    private function getCartById($cartId)
    {
        return $this->em->getRepository("AppBundle:Cart")->findOneBy(array("id" => $cartId));
    }


}