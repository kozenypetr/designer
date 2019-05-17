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
 * @ORM\HasLifecycleCallbacks()
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
     * @var string
     *
     * @ORM\Column(name="gopay_id", type="string", length=255, nullable=true)
     */
    private $gopayId;


    /**
     * @var string
     *
     * @ORM\Column(name="gopay_state", type="string", length=255, nullable=true)
     */
    private $gopayState;


    /**
     * @var string
     *
     * @ORM\Column(name="gopay_substate", type="string", length=255, nullable=true)
     */
    private $gopaySubstate;

    /**
     * @var string
     *
     * @ORM\Column(name="gopay_gw_url", type="string", length=255, nullable=true)
     */
    private $gopayGwUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="gopay_js_url", type="string", length=255, nullable=true)
     */
    private $gopayJsUrl;


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
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
     */
    private $hash;

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
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist", "remove"})
     */
    protected $items;

    /**
     * Cart
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="OrderStatusHistory", mappedBy="order", cascade={"persist", "remove"})
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
    /**
     * @ORM\PrePersist
     */
    public function createHash()
    {
        $hash = md5(rand(0, 1000000) . $this->getBillingName() . 'sdA4df6rTfd');
        $this->setHash($hash);
    }


    public function getGopaySubstateText()
    {
        $statuses = [];

        $statuses['_101']  = 'Čekáme na provedení online platby.';
        $statuses['_3001'] = 'Bankovní platba potvrzena avízem.';
        $statuses['_3002'] = 'Bankovní platba potvrzena výpisem.';
        $statuses['_3003'] = 'Bankovní platba nebyla potvrzena.';
        $statuses['_5001'] = 'Schváleno s nulovou částkou';
        $statuses['_5002'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu dosažení limitů na platební kartě.';
        $statuses['_5003'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu problémů na straně vydavatele platební karty.';
        $statuses['_5004'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu problému na straně vydavatele platební karty.';
        $statuses['_5005'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu zablokované platební karty.';
        $statuses['_5006'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu nedostatku peněžních prostředků na platební kartě.';
        $statuses['_5007'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu expirované platební karty.';
        $statuses['_5008'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu zamítnutí CVV/CVC kódu.';
        $statuses['_5009'] = 'Zamítnutí platby v systému 3D Secure banky zákazníka.';
        $statuses['_5015'] = 'Zamítnutí platby v systému 3D Secure banky zákazníka.';
        $statuses['_5017'] = 'Zamítnutí platby v systému 3D Secure banky zákazníka.';
        $statuses['_5018'] = 'Zamítnutí platby v systému 3D Secure banky zákazníka.';
        $statuses['_5019'] = 'Zamítnutí platby v systému 3D Secure banky zákazníka.';
        $statuses['_6502'] = 'Zamítnutí platby v systému 3D Secure banky zákazníka.';
        $statuses['_6504'] = 'Zamítnutí platby v systému 3D Secure banky zákazníka.';
        $statuses['_5010'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu problémů na platební kartě.';
        $statuses['_5014'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu problémů na platební kartě.';
        $statuses['_5011'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu problémů na účtu platební karty.';
        $statuses['_5036'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu problémů na účtu platební karty.';
        $statuses['_5012'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu technických problémů v autorizačním centru banky zákazníka.';
        $statuses['_5013'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu chybného zadání čísla platební karty.';
        $statuses['_5016'] = 'Zamítnutí platby v autorizačním centru banky zákazníka, platba nebyla povolena na platební kartě zákazníka.';
        $statuses['_5020'] = 'Neznámá konfigurace.';
        $statuses['_5021'] = 'Zamítnutí platby v autorizačním centru banky zákazníka z důvodu dosažení nastavených limitů na platební kartě.';
        $statuses['_5022'] = 'Nastal technický problém spojený s autorizačním centrem banky zákazníka.';
        $statuses['_5023'] = 'Platba nebyla provedena.';
        $statuses['_5038'] = 'Platba nebyla provedena.';
        $statuses['_5024'] = 'Platba nebyla provedena. Platební údaje nebyly zadány v časovém limitu na platební bráně.';
        $statuses['_5025'] = 'Platba nebyla provedena. Konkrétní důvod zamítnutí je sdělen přímo zákazníkovi.';
        $statuses['_5026'] = 'Platba nebyla provedena. Součet kreditovaných částek překročil uhrazenou částku.';
        $statuses['_5027'] = 'Platba nebyla provedena. Uživatel není oprávněn k provedení operace.';
        $statuses['_5028'] = 'Platba nebyla provedena. Částka k úhradě překročila autorizovanou částku.';
        $statuses['_5029'] = 'Platba zatím nebyla provedena.';
        $statuses['_5030'] = 'Platba nebyla provedena z důvodu opakovaného zadání platby.';
        $statuses['_5031'] = 'Při platbě nastal technický problém na straně banky.';
        $statuses['_5033'] = 'SMS se nepodařilo doručit.';
        $statuses['_5035'] = 'Platební karta je vydaná v regionu, ve kterém nejsou podporovány platby kartou.';
        $statuses['_5037'] = 'Držitel platební karty zrušil platbu.';
        $statuses['_5039'] = 'Platba byla zamítnuta v autorizačním centru banky zákazníka z důvodu zablokované platební karty.';
        $statuses['_5040'] = 'Duplicitni reversal transakce';
        $statuses['_5041'] = 'Duplicitní transakce';
        $statuses['_5042'] = 'Bankovní platba byla zamítnuta.';
        $statuses['_5043'] = 'Platba zrušena uživatelem.';
        $statuses['_5044'] = 'SMS byla odeslána. Zatím se ji nepodařilo doručit.';
        $statuses['_5045'] = 'Platba byla přijata. Platba bude připsána po zpracování v síti Bitcoin.';
        $statuses['_5046'] = 'Platba nebyla uhrazena v plné výši.';
        $statuses['_5047'] = 'Platba byla provedena po splatnosti.';

        $output = null;
        if ($this->getGopaySubstate() && isset($statuses[$this->getGopaySubstate()]))
        {
            $output = $statuses[$this->getGopaySubstate()];
        }
        return '<p>' . $output . '</p>';
    }

    public function isGopay()
    {
        return preg_match('|gopay|', $this->getPaymentCode());
    }

    public function changeStatus($status, $message = null)
    {
        $this->setStatus($status);
        $history = new OrderStatusHistory();
        $history->setStatus($status);
        $history->setOrder($this);
        $history->setMessage($message);

        $this->addHistory($history);
    }


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


    public function getDatalayerFormatItems()
    {
        $items = [];
        foreach ($this->getItems() as $item)
        {
            $items[] = "{name: '{$item->getName()}', sku: '{$item->getModel()}', price: {$item->getPrice()}, quantity: '{$item->getQuantity()}'}";
        }
        return join(',', $items);
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

    /**
     * Set gopayId.
     *
     * @param string $gopayId
     *
     * @return Order
     */
    public function setGopayId($gopayId)
    {
        $this->gopayId = $gopayId;

        return $this;
    }

    /**
     * Get gopayId.
     *
     * @return string
     */
    public function getGopayId()
    {
        return $this->gopayId;
    }

    /**
     * Set gopayState.
     *
     * @param string $gopayState
     *
     * @return Order
     */
    public function setGopayState($gopayState)
    {
        $this->gopayState = $gopayState;

        return $this;
    }

    /**
     * Get gopayState.
     *
     * @return string
     */
    public function getGopayState()
    {
        return $this->gopayState;
    }

    /**
     * Set gopayGwUrl.
     *
     * @param string $gopayGwUrl
     *
     * @return Order
     */
    public function setGopayGwUrl($gopayGwUrl)
    {
        $this->gopayGwUrl = $gopayGwUrl;

        return $this;
    }

    /**
     * Get gopayGwUrl.
     *
     * @return string
     */
    public function getGopayGwUrl()
    {
        return $this->gopayGwUrl;
    }

    /**
     * Set gopayJsUrl.
     *
     * @param string $gopayJsUrl
     *
     * @return Order
     */
    public function setGopayJsUrl($gopayJsUrl)
    {
        $this->gopayJsUrl = $gopayJsUrl;

        return $this;
    }

    /**
     * Get gopayJsUrl.
     *
     * @return string
     */
    public function getGopayJsUrl()
    {
        return $this->gopayJsUrl;
    }

    /**
     * Set gopaySubstate.
     *
     * @param string|null $gopaySubstate
     *
     * @return Order
     */
    public function setGopaySubstate($gopaySubstate = null)
    {
        $this->gopaySubstate = $gopaySubstate;

        return $this;
    }

    /**
     * Get gopaySubstate.
     *
     * @return string|null
     */
    public function getGopaySubstate()
    {
        return $this->gopaySubstate;
    }

    /**
     * Set hash.
     *
     * @param string|null $hash
     *
     * @return Order
     */
    public function setHash($hash = null)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash.
     *
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash;
    }
}
