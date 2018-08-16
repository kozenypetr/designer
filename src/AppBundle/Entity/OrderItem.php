<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\Product;
use AppBundle\Entity\Cart;


/**
 * OrderItem
 *
 * @ORM\Table(name="shop_order_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderItemRepository")
 */
class OrderItem
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
     * Order
     *
     * @var Cart
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items", cascade={"persist"})
     */
    protected $order;

    /**
     * Many order item have one product
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255, nullable=true)
     */
    private $model;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=4)
     */
    private $price;

    /**
     * @var decimal
     *
     * @ORM\Column(name="tax", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $tax;

    /**
     * @var json
     *
     * @ORM\Column(name="parameters", type="json", nullable=true)
     */
    private $parameters;

    /**
     * @var json
     *
     * @ORM\Column(name="attributes", type="json", nullable=true)
     */
    private $attributes;

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


    public function __toString() {
        return (string)$this->getId();
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
     * Set model.
     *
     * @param string|null $model
     *
     * @return OrderItem
     */
    public function setModel($model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set quantity.
     *
     * @param int $quantity
     *
     * @return OrderItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price.
     *
     * @param string $price
     *
     * @return OrderItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set tax.
     *
     * @param string|null $tax
     *
     * @return OrderItem
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
     * Set parameters.
     *
     * @param json|null $parameters
     *
     * @return OrderItem
     */
    public function setParameters($parameters = null)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameters.
     *
     * @return json|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set attributes.
     *
     * @param json|null $attributes
     *
     * @return OrderItem
     */
    public function setAttributes($attributes = null)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return json|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return OrderItem
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
     * @return OrderItem
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
     * Set product.
     *
     * @param \AppBundle\Entity\Product|null $product
     *
     * @return OrderItem
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.
     *
     * @return \AppBundle\Entity\Product|null
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return OrderItem
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cart.
     *
     * @param \AppBundle\Entity\Order|null $cart
     *
     * @return OrderItem
     */
    public function setCart(\AppBundle\Entity\Order $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart.
     *
     * @return \AppBundle\Entity\Order|null
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set order.
     *
     * @param \AppBundle\Entity\Order|null $order
     *
     * @return OrderItem
     */
    public function setOrder(\AppBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return \AppBundle\Entity\Order|null
     */
    public function getOrder()
    {
        return $this->order;
    }
}
