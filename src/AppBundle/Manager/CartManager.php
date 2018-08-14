<?php

/**
 *
 */

namespace AppBundle\Manager;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItem;
use AppBundle\Entity\Product;

/**
 * Class CartManager
 *
 * @package AppBundle\Manager
 */
class CartManager {

    protected $session    = null;
    protected $em         = null;
    protected $tokenStorage = null;
    public    $cart = null;


    /**
     * @param Session $session
     * @param EntityManager $em
     * @param TokenStorage $tokenStorage
     */
    public function __construct(Session $session, EntityManagerInterface $em, TokenStorage $tokenStorage)
    {
        $this->session = $session;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->cart = $this->getCart();
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


    /**
     * @param Product
     * @param int $quantity
     * @return bool
     */
    public function addItem($product, $quantity = 1, $attributes = null, $parameters = null)
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

        $attributesHash = '';
        if ($attributes)
        {
            $attributesHash = md5(serialize($attributes));
        }
        $cartItem->setAttributesHash($attributesHash);

        $cart->addItem($cartItem);

        $this->em->persist($cartItem);
        $this->em->persist($cart);
        $this->em->flush();

        return true;
    }


    private function findCartItem($cart, $product, $attributes)
    {
        $attributesHash = '';
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
    private function loadCart()
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
        /*if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY'))
            $cart->setUser($this->securityContext->getToken()->getUser());*/

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