<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\OrderStatus;
use AppBundle\Entity\Shipping;


/**
 * Product
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentRepository")
 */
class Payment
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
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", options={"default": true})
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2)
     */
    private $locale = "cs";

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="annotation", type="string", length=255, nullable=true)
     */
    private $annotation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;


    /**
     * @var string
     *
     * @ORM\Column(name="price_table", type="string", length=255, nullable=true)
     */
    private $priceTable;

    
    /**
     * @ORM\ManyToMany(targetEntity="Shipping", inversedBy="payments")
     */
    private $shippings;


    /**
     * Stav objednavky
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status = null;


    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=255, nullable=true)
     */
    private $time;


    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort = 1000;

    
    public function __construct()
    {
      $this->shippings = new ArrayCollection();
    }


    public function __toString() {
        return $this->getName()?$this->getName():'Platba';
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
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return Payment
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set locale.
     *
     * @param string $locale
     *
     * @return Payment
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Payment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Payment
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Payment
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set priceTable.
     *
     * @param string|null $priceTable
     *
     * @return Payment
     */
    public function setPriceTable($priceTable = null)
    {
        $this->priceTable = $priceTable;

        return $this;
    }

    /**
     * Get priceTable.
     *
     * @return string|null
     */
    public function getPriceTable()
    {
        return $this->priceTable;
    }

    /**
     * Set sort.
     *
     * @param int|null $sort
     *
     * @return Payment
     */
    public function setSort($sort = null)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort.
     *
     * @return int|null
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Add shipping.
     *
     * @param \AppBundle\Entity\Shipping $shipping
     *
     * @return Payment
     */
    public function addShipping(\AppBundle\Entity\Shipping $shipping)
    {
        $this->shippings[] = $shipping;

        return $this;
    }

    /**
     * Remove shipping.
     *
     * @param \AppBundle\Entity\Shipping $shipping
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeShipping(\AppBundle\Entity\Shipping $shipping)
    {
        return $this->shippings->removeElement($shipping);
    }

    /**
     * Get shippings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShippings()
    {
        return $this->shippings;
    }

    /**
     * Set annotation.
     *
     * @param string $annotation
     *
     * @return Payment
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * Get annotation.
     *
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Set icon.
     *
     * @param string $icon
     *
     * @return Payment
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set time.
     *
     * @param string $time
     *
     * @return Payment
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time.
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set status.
     *
     * @param \AppBundle\Entity\OrderStatus|null $status
     *
     * @return Payment
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
}
