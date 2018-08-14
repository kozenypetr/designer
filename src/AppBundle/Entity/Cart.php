<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\BaseCustomer;
use AppBundle\Entity\Shipping;
use AppBundle\Entity\Payment;


/**
 * Product
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart extends BaseCustomer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Many Cart have One Shipping.
     * @ORM\ManyToOne(targetEntity="Shipping")
     * @ORM\JoinColumn(name="shipping_id", referencedColumnName="id")
     */
    private $shipping;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_parameters", type="json", nullable=true)
     */
    private $shippingParameters;

    /**
     * Many Cart have One Payment.
     * @ORM\ManyToOne(targetEntity="Payment")
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id")
     */
    private $payment;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_parameters", type="json", nullable=true)
     */
    private $paymentParameters;

    /**
     * @var string
     *
     * @ORM\Column(name="discount_coupon", type="string", length=255, nullable=true)
     */
    private $dicountCoupon;


    /**
     * @var string
     *
     * @ORM\Column(name="order_note", type="string", length=255, nullable=true)
     */
    private $orderNote;

    /**
     * Cart
     *
     * @var CartItem
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart")
     */
    protected $items;
    
    /*protected $user;*/

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;


    public function __construct()
    {
      $this->items = new ArrayCollection();
    }


    public function getTotal()
    {
        $total = 0;
        foreach ($this->getItems() as $item)
        {
            $total += $item->getPrice();
        }

        return $total;
    }

    /**
     * Informace o fakturacni adrese v poli
     * @return array
     */
    public function getBillingData()
    {
        return [
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'billing_name' => $this->getBillingName(),
            'billing_address' => $this->getBillingAddress(),
            'billing_city' => $this->getBillingCity(),
            'billing_postcode' => $this->getBillingPostcode(),
            'is_delivery' => $this->getIsDelivery()
        ];
    }

    /**
     * Informace o dodaci adrese v poli
     * @return array
     */
    public function getDeliveryData()
    {
        return [
            'delivery_name' => $this->getDeliveryName(),
            'delivery_address' => $this->getDeliveryAddress(),
            'delivery_city' => $this->getDeliveryCity(),
            'delivery_postcode' => $this->getDeliveryPostcode(),
        ];
    }

    public function setBillingData($data)
    {
        $this->setEmail($data['email']);
        $this->setPhone($data['phone']);
        $this->setBillingName($data['billing_name']);
        $this->setBillingAddress($data['billing_address']);
        $this->setBillingCity($data['billing_city']);
        $this->setBillingPostcode($data['billing_postcode']);
        $this->setIsDelivery($data['is_delivery']);
    }

    public function setDeliveryData($data)
    {
        $this->setDeliveryName($data['delivery_name']);
        $this->setDeliveryAddress($data['delivery_address']);
        $this->setDeliveryCity($data['delivery_city']);
        $this->setDeliveryPostcode($data['delivery_postcode']);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Cart
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return Cart
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add item.
     *
     * @param \AppBundle\Entity\CartItem $item
     *
     * @return Cart
     */
    public function addItem(\AppBundle\Entity\CartItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item.
     *
     * @param \AppBundle\Entity\CartItem $item
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeItem(\AppBundle\Entity\CartItem $item)
    {
        return $this->items->removeElement($item);
    }

    /**
     * Get items.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set shipping.
     *
     * @param string|null $shipping
     *
     * @return Cart
     */
    public function setShipping($shipping = null)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * Get shipping.
     *
     * @return string|null
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Set shippingParameters.
     *
     * @param json|null $shippingParameters
     *
     * @return Cart
     */
    public function setShippingParameters($shippingParameters = null)
    {
        $this->shippingParameters = $shippingParameters;

        return $this;
    }

    /**
     * Get shippingParameters.
     *
     * @return json|null
     */
    public function getShippingParameters()
    {
        return $this->shippingParameters;
    }

    /**
     * Set payment.
     *
     * @param string|null $payment
     *
     * @return Cart
     */
    public function setPayment($payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment.
     *
     * @return string|null
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * Set paymentParameters.
     *
     * @param json|null $paymentParameters
     *
     * @return Cart
     */
    public function setPaymentParameters($paymentParameters = null)
    {
        $this->paymentParameters = $paymentParameters;

        return $this;
    }

    /**
     * Get paymentParameters.
     *
     * @return json|null
     */
    public function getPaymentParameters()
    {
        return $this->paymentParameters;
    }

    /**
     * Set dicountCoupon.
     *
     * @param string|null $dicountCoupon
     *
     * @return Cart
     */
    public function setDicountCoupon($dicountCoupon = null)
    {
        $this->dicountCoupon = $dicountCoupon;

        return $this;
    }

    /**
     * Get dicountCoupon.
     *
     * @return string|null
     */
    public function getDicountCoupon()
    {
        return $this->dicountCoupon;
    }

    /**
     * Set orderNote.
     *
     * @param string|null $orderNote
     *
     * @return Cart
     */
    public function setOrderNote($orderNote = null)
    {
        $this->orderNote = $orderNote;

        return $this;
    }

    /**
     * Get orderNote.
     *
     * @return string|null
     */
    public function getOrderNote()
    {
        return $this->orderNote;
    }
}
