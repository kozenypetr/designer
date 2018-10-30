<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;


/**
 * Product
 *
 * @ORM\Table(name="order_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderStatusRepository")
 */
class OrderStatus
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort = 1000;


    // === add methods
    public function __toString() {
        return (string)$this->name;
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
     * @return OrderStatus
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
     * Set name.
     *
     * @param string $name
     *
     * @return OrderStatus
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
     * Set annotation.
     *
     * @param string|null $annotation
     *
     * @return OrderStatus
     */
    public function setAnnotation($annotation = null)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * Get annotation.
     *
     * @return string|null
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return OrderStatus
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
     * Set color.
     *
     * @param string|null $color
     *
     * @return OrderStatus
     */
    public function setColor($color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set sort.
     *
     * @param int|null $sort
     *
     * @return OrderStatus
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
}
