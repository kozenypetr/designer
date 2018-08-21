<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\Product;
use AppBundle\Entity\AttributeOption;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


/**
 * Order
 *
 * @ORM\Table(name="attribute")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttributeRepository")
 */
class Attribute
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
     * Product
     *
     * @var Product
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="attributes")
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_required", type="boolean", options={"default": false})
     */
    private $isRequired = false;

    /**
     * Options
     *
     * @var AttributeOption
     * @ORM\OneToMany(targetEntity="AttributeOption", mappedBy="attribute", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $options;

    /**
     * @var string
     *
     * @ORM\Column(name="modul", type="string", length=255, nullable=true)
     */
    private $modul;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255, nullable=true)
     */
    private $class;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;


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
      $this->options = new ArrayCollection();
    }

    public function __toString() {
        return $this->getName();
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
     * Set name.
     *
     * @param string|null $name
     *
     * @return Attribute
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
     * Set isRequired.
     *
     * @param bool $isRequired
     *
     * @return Attribute
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    /**
     * Get isRequired.
     *
     * @return bool
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * Set modul.
     *
     * @param string|null $modul
     *
     * @return Attribute
     */
    public function setModul($modul = null)
    {
        $this->modul = $modul;

        return $this;
    }

    /**
     * Get modul.
     *
     * @return string|null
     */
    public function getModul()
    {
        return $this->modul;
    }

    /**
     * Set class.
     *
     * @param string|null $class
     *
     * @return Attribute
     */
    public function setClass($class = null)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set type.
     *
     * @param string|null $type
     *
     * @return Attribute
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Attribute
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
     * @return Attribute
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
     * Add option.
     *
     * @param \AppBundle\Entity\AttributeOption $option
     *
     * @return Attribute
     */
    public function addOption(\AppBundle\Entity\AttributeOption $option)
    {
        $option->setAttribute($this);

        $this->options[] = $option;

        return $this;
    }

    /**
     * Remove option.
     *
     * @param \AppBundle\Entity\AttributeOption $option
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOption(\AppBundle\Entity\AttributeOption $option)
    {
        return $this->options->removeElement($option);
    }

    /**
     * Get options.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set product.
     *
     * @param \AppBundle\Entity\Product|null $product
     *
     * @return Attribute
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
}
