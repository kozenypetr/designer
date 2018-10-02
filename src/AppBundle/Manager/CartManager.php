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
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderItem;
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

    /**
     * @var Cart
     */
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

    public function finishOrder()
    {
        $order = new Order();
        $order->setBillingData($this->cart->getBillingData());
        $order->setDeliveryData($this->cart->getDeliveryData());

        $order->setSubtotal($this->cart->getSubtotal());
        $order->setTax($this->cart->getTax());
        $order->setTotal($this->cart->getTotal());

        $order->setShippingName($this->cart->getShipping()->getName());
        $order->setShippingCode($this->cart->getShipping()->getCode());

        $order->setPaymentName($this->cart->getPayment()->getName());
        $order->setPaymentCode($this->cart->getPayment()->getCode());

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

            $this->em->persist($orderItem);
        }

        $this->em->remove($this->cart);
        $this->session->set('cart', null);

        $this->em->flush();

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

    public function getSummary()
    {
        $summary = [];
        $summary['count'] = $this->cart->getItems()->count();
        $summary['total'] = $this->cart->getTotal();

        return $summary;
    }

    /**
     * @param Product
     * @param int $quantity
     * @param array $attributes
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

        $productAttributes = array();
        foreach ($product->getAttributes() as $attribute)
        {
            $productAttributes[$attribute->getId()] = $attribute;
        }

        $attributesHash = '';
        if ($attributes && $productAttributes)
        {
            $attributesSaveData = [];
            foreach ($attributes as $id => $value)
            {
                if (isset($productAttributes[$id])) {
                    $attributesSaveData[$id] = [
                        'name' => $productAttributes[$id]->getName(),
                        'value' => $value
                    ];
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