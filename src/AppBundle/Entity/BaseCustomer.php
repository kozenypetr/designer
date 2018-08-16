<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BaseCustomer
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseCustomer
{

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delivery", type="boolean", options={"default": false})
     */
    private $isDelivery = false;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_name", type="string", length=255, nullable=true)
     */
    private $deliveryName;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_address", type="string", length=255, nullable=true)
     */
    private $deliveryAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_city", type="string", length=255, nullable=true)
     */
    private $deliveryCity;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_postcode", type="string", length=255, nullable=true)
     */
    private $deliveryPostcode;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_note", type="text", nullable=true)
     */
    private $deliveryNote;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_name", type="string", length=255, nullable=true)
     */
    private $billingName;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_address", type="string", length=255, nullable=true)
     */
    private $billingAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_city", type="string", length=255, nullable=true)
     */
    private $billingCity;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_postcode", type="string", length=255, nullable=true)
     */
    private $billingPostcode;


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
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return BaseCustomer
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set deliveryName.
     *
     * @param string|null $deliveryName
     *
     * @return BaseCustomer
     */
    public function setDeliveryName($deliveryName = null)
    {
        $this->deliveryName = $deliveryName;

        return $this;
    }

    /**
     * Get deliveryName.
     *
     * @return string|null
     */
    public function getDeliveryName()
    {
        return $this->deliveryName;
    }

    /**
     * Set deliveryAddress.
     *
     * @param string|null $deliveryAddress
     *
     * @return BaseCustomer
     */
    public function setDeliveryAddress($deliveryAddress = null)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get deliveryAddress.
     *
     * @return string|null
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * Set deliveryCity.
     *
     * @param string|null $deliveryCity
     *
     * @return BaseCustomer
     */
    public function setDeliveryCity($deliveryCity = null)
    {
        $this->deliveryCity = $deliveryCity;

        return $this;
    }

    /**
     * Get deliveryCity.
     *
     * @return string|null
     */
    public function getDeliveryCity()
    {
        return $this->deliveryCity;
    }

    /**
     * Set deliveryPostcode.
     *
     * @param string|null $deliveryPostcode
     *
     * @return BaseCustomer
     */
    public function setDeliveryPostcode($deliveryPostcode = null)
    {
        $this->deliveryPostcode = $deliveryPostcode;

        return $this;
    }

    /**
     * Get deliveryPostcode.
     *
     * @return string|null
     */
    public function getDeliveryPostcode()
    {
        return $this->deliveryPostcode;
    }

    /**
     * Set deliveryNote.
     *
     * @param string|null $deliveryNote
     *
     * @return BaseCustomer
     */
    public function setDeliveryNote($deliveryNote = null)
    {
        $this->deliveryNote = $deliveryNote;

        return $this;
    }

    /**
     * Get deliveryNote.
     *
     * @return string|null
     */
    public function getDeliveryNote()
    {
        return $this->deliveryNote;
    }

    /**
     * Set billingName.
     *
     * @param string|null $billingName
     *
     * @return BaseCustomer
     */
    public function setBillingName($billingName = null)
    {
        $this->billingName = $billingName;

        return $this;
    }

    /**
     * Get billingName.
     *
     * @return string|null
     */
    public function getBillingName()
    {
        return $this->billingName;
    }

    /**
     * Set billingAddress.
     *
     * @param string|null $billingAddress
     *
     * @return BaseCustomer
     */
    public function setBillingAddress($billingAddress = null)
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    /**
     * Get billingAddress.
     *
     * @return string|null
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set billingCity.
     *
     * @param string|null $billingCity
     *
     * @return BaseCustomer
     */
    public function setBillingCity($billingCity = null)
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    /**
     * Get billingCity.
     *
     * @return string|null
     */
    public function getBillingCity()
    {
        return $this->billingCity;
    }

    /**
     * Set billingPostcode.
     *
     * @param string|null $billingPostcode
     *
     * @return BaseCustomer
     */
    public function setBillingPostcode($billingPostcode = null)
    {
        $this->billingPostcode = $billingPostcode;

        return $this;
    }

    /**
     * Get billingPostcode.
     *
     * @return string|null
     */
    public function getBillingPostcode()
    {
        return $this->billingPostcode;
    }

    /**
     * Set isDelivery.
     *
     * @param bool $isDelivery
     *
     * @return BaseCustomer
     */
    public function setIsDelivery($isDelivery)
    {
        $this->isDelivery = $isDelivery;

        return $this;
    }

    /**
     * Get isDelivery.
     *
     * @return bool
     */
    public function getIsDelivery()
    {
        return $this->isDelivery;
    }
}
