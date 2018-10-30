<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\BaseCustomer;
use AppBundle\Entity\Shipping;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Customer;
use AppBundle\Entity\OrderStatus;
use AppBundle\Entity\OrderStatusHistory;


/**
 * Order
 *
 * @ORM\Table(name="shop_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order extends BaseCustomer
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * Many Cart have One Shipping.
     * @ORM\ManyToOne(targetEntity="Shipping")
     * @ORM\JoinColumn(name="shipping_id", referencedColumnName="id")
     */
    private $shipping;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_name", type="string", length=255)
     */
    private $shippingName;

    /**
     * @var string
     *
     * @ORM\Column(name="shipping_code", type="string", length=255)
     */
    private $shippingCode;

    /**
     * @var decimal
     *
     * @ORM\Column(name="shipping_price", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $shippingPrice;

    /**
     * @var decimal
     *
     * @ORM\Column(name="shipping_price_tax", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $shippingPriceTax;

    /**
     * @var json
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
     * @ORM\Column(name="payment_name", type="string", length=255)
     */
    private $paymentName;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_code", type="string", length=255)
     */
    private $paymentCode;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_price", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $paymentPrice;

    /**
     * @var decimal
     *
     * @ORM\Column(name="payment_price_tax", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $paymentPriceTax;

    /**
     * @var json
     *
     * @ORM\Column(name="payment_parameters", type="json", nullable=true)
     */
    private $paymentParameters;

    /**
     * @var decimal
     *
     * @ORM\Column(name="subtotal", type="decimal", precision=15, scale=4)
     */
    private $subtotal;

    /**
     * @var decimal
     *
     * @ORM\Column(name="tax", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $tax;

    /**
     * @var decimal
     *
     * @ORM\Column(name="total", type="decimal", precision=15, scale=4)
     */
    private $total;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist"})
     */
    protected $items;

    /**
     * Cart
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderStatusHistory", mappedBy="order", cascade={"persist"})
     */
    protected $history;

    /**
     * Zakaznik
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer = null;

    /**
     * Stav objednavky
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status = null;

    /**
     * @var decimal $weight
     *
     * @ORM\Column(name="weight", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $weight;

    /**
     * @var string $packageId
     *
     * @ORM\Column(name="package_id", type="string", length=255, nullable=true)
     */
    private $packageId;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(name="estimated_delivery_date", type="datetime", nullable=true)
     */
    private $estimatedDeliveryDate;

    
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
      $this->items   = new ArrayCollection();
      $this->history = new ArrayCollection();
    }

    // === add methods ===
    public function estDateFormat()
    {
        return $this->getEstimatedDeliveryDate();
    }

    public function getPaymentReference()
    {
        return $this->getId();
    }

    // === generated methods

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
     * Set email.
     *
     * @param string|null $email
     *
     * @return Order
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set shippingName.
     *
     * @param string $shippingName
     *
     * @return Order
     */
    public function setShippingName($shippingName)
    {
        $this->shippingName = $shippingName;

        return $this;
    }

    /**
     * Get shippingName.
     *
     * @return string
     */
    public function getShippingName()
    {
        return $this->shippingName;
    }

    /**
     * Set shippingCode.
     *
     * @param string $shippingCode
     *
     * @return Order
     */
    public function setShippingCode($shippingCode)
    {
        $this->shippingCode = $shippingCode;

        return $this;
    }

    /**
     * Get shippingCode.
     *
     * @return string
     */
    public function getShippingCode()
    {
        return $this->shippingCode;
    }

    /**
     * Set shippingPrice.
     *
     * @param string|null $shippingPrice
     *
     * @return Order
     */
    public function setShippingPrice($shippingPrice = null)
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }

    /**
     * Get shippingPrice.
     *
     * @return string|null
     */
    public function getShippingPrice()
    {
        return $this->shippingPrice;
    }

    /**
     * Set shippingPriceTax.
     *
     * @param string|null $shippingPriceTax
     *
     * @return Order
     */
    public function setShippingPriceTax($shippingPriceTax = null)
    {
        $this->shippingPriceTax = $shippingPriceTax;

        return $this;
    }

    /**
     * Get shippingPriceTax.
     *
     * @return string|null
     */
    public function getShippingPriceTax()
    {
        return $this->shippingPriceTax;
    }

    /**
     * Set shippingParameters.
     *
     * @param json|null $shippingParameters
     *
     * @return Order
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
     * Set paymentName.
     *
     * @param string $paymentName
     *
     * @return Order
     */
    public function setPaymentName($paymentName)
    {
        $this->paymentName = $paymentName;

        return $this;
    }

    /**
     * Get paymentName.
     *
     * @return string
     */
    public function getPaymentName()
    {
        return $this->paymentName;
    }

    /**
     * Set paymentCode.
     *
     * @param string $paymentCode
     *
     * @return Order
     */
    public function setPaymentCode($paymentCode)
    {
        $this->paymentCode = $paymentCode;

        return $this;
    }

    /**
     * Get paymentCode.
     *
     * @return string
     */
    public function getPaymentCode()
    {
        return $this->paymentCode;
    }

    /**
     * Set paymentPrice.
     *
     * @param string|null $paymentPrice
     *
     * @return Order
     */
    public function setPaymentPrice($paymentPrice = null)
    {
        $this->paymentPrice = $paymentPrice;

        return $this;
    }

    /**
     * Get paymentPrice.
     *
     * @return string|null
     */
    public function getPaymentPrice()
    {
        return $this->paymentPrice;
    }

    /**
     * Set paymentPriceTax.
     *
     * @param string|null $paymentPriceTax
     *
     * @return Order
     */
    public function setPaymentPriceTax($paymentPriceTax = null)
    {
        $this->paymentPriceTax = $paymentPriceTax;

        return $this;
    }

    /**
     * Get paymentPriceTax.
     *
     * @return string|null
     */
    public function getPaymentPriceTax()
    {
        return $this->paymentPriceTax;
    }

    /**
     * Set paymentParameters.
     *
     * @param json|null $paymentParameters
     *
     * @return Order
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
     * Set subtotal.
     *
     * @param string $subtotal
     *
     * @return Order
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * Get subtotal.
     *
     * @return string
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Set tax.
     *
     * @param string|null $tax
     *
     * @return Order
     */
    public function setTax($tax = null)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax.
     *
     * @return string|null
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set total.
     *
     * @param string $total
     *
     * @return Order
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set dicountCoupon.
     *
     * @param string|null $dicountCoupon
     *
     * @return Order
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
     * @return Order
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

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Order
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
     * @return Order
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
     * @param \AppBundle\Entity\OrderItem $item
     *
     * @return Order
     */
    public function addItem(\AppBundle\Entity\OrderItem $item)
    {
        $item->setOrder($this);

        $this->items[] = $item;

        $item->setOrder($this);

        return $this;
    }

    /**
     * Remove item.
     *
     * @param \AppBundle\Entity\OrderItem $item
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeItem(\AppBundle\Entity\OrderItem $item)
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
     * Set customer.
     *
     * @param \AppBundle\Entity\Customer|null $customer
     *
     * @return Order
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer.
     *
     * @return \AppBundle\Entity\Customer|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set status.
     *
     * @param \AppBundle\Entity\OrderStatus|null $status
     *
     * @return Order
     */
    public function setStatus(\AppBundle\Entity\OrderStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return \AppBundle\Entity\OrderStatus|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add history.
     *
     * @param \AppBundle\Entity\OrderStatusHistory $history
     *
     * @return Order
     */
    public function addHistory(\AppBundle\Entity\OrderStatusHistory $history)
    {
        $history->setOrder($this);

        $this->history[] = $history;

        return $this;
    }

    /**
     * Remove history.
     *
     * @param \AppBundle\Entity\OrderStatusHistory $history
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeHistory(\AppBundle\Entity\OrderStatusHistory $history)
    {
        return $this->history->removeElement($history);
    }

    /**
     * Get history.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set packageId.
     *
     * @param string|null $packageId
     *
     * @return Order
     */
    public function setPackageId($packageId = null)
    {
        $this->packageId = $packageId;

        return $this;
    }

    /**
     * Get packageId.
     *
     * @return string|null
     */
    public function getPackageId()
    {
        return $this->packageId;
    }

    /**
     * Set estimatedDeliveryDate.
     *
     * @param \DateTime|null $estimatedDeliveryDate
     *
     * @return Order
     */
    public function setEstimatedDeliveryDate($estimatedDeliveryDate = null)
    {
        $this->estimatedDeliveryDate = $estimatedDeliveryDate;

        return $this;
    }

    /**
     * Get estimatedDeliveryDate.
     *
     * @return \DateTime|null
     */
    public function getEstimatedDeliveryDate()
    {
        return $this->estimatedDeliveryDate;
    }

    /**
     * Set weight.
     *
     * @param string|null $weight
     *
     * @return Order
     */
    public function setWeight($weight = null)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight.
     *
     * @return string|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set shipping.
     *
     * @param \AppBundle\Entity\Shipping|null $shipping
     *
     * @return Order
     */
    public function setShipping(\AppBundle\Entity\Shipping $shipping = null)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * Get shipping.
     *
     * @return \AppBundle\Entity\Shipping|null
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Set payment.
     *
     * @param \AppBundle\Entity\Payment|null $payment
     *
     * @return Order
     */
    public function setPayment(\AppBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment.
     *
     * @return \AppBundle\Entity\Payment|null
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
