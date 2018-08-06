<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Product\Translation")
 */
class Product extends AbstractPersonalTranslatable implements TranslatableInterface
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
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @Gedmo\Translatable
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="annotation", type="string", length=255, nullable=true)
     */
    private $annotation;

    /**
     * @var string
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
     * @ORM\JoinTable(
     *      name="product_category",
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $categories;

    /**
     * One Product has Many Images.
     * @ORM\OneToMany(targetEntity="Image", mappedBy="product", cascade={"persist", "remove"})
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $images;


    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", precision=15, scale=4)
     */
    private $price;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price1", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $price1;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price2", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $price2;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price3", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $price3;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price4", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $price4;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Product\Translation",
     *     mappedBy="object",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    
    public function __construct()
    {
      $this->categories = new ArrayCollection();
      $this->images     = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set annotation
     *
     * @param string $annotation
     *
     * @return Product
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * Get annotation
     *
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Product
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Category $category
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Product
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price1
     *
     * @param string $price1
     *
     * @return Product
     */
    public function setPrice1($price1)
    {
        $this->price1 = $price1;

        return $this;
    }

    /**
     * Get price1
     *
     * @return string
     */
    public function getPrice1()
    {
        return $this->price1;
    }

    /**
     * Set price2
     *
     * @param string $price2
     *
     * @return Product
     */
    public function setPrice2($price2)
    {
        $this->price2 = $price2;

        return $this;
    }

    /**
     * Get price2
     *
     * @return string
     */
    public function getPrice2()
    {
        return $this->price2;
    }

    /**
     * Set price3
     *
     * @param string $price3
     *
     * @return Product
     */
    public function setPrice3($price3)
    {
        $this->price3 = $price3;

        return $this;
    }

    /**
     * Get price3
     *
     * @return string
     */
    public function getPrice3()
    {
        return $this->price3;
    }

    /**
     * Set price4
     *
     * @param string $price4
     *
     * @return Product
     */
    public function setPrice4($price4)
    {
        $this->price4 = $price4;

        return $this;
    }

    /**
     * Get price4
     *
     * @return string
     */
    public function getPrice4()
    {
        return $this->price4;
    }

    /**
     * Remove translation.
     *
     * @param \AppBundle\Entity\Product\Translation $translation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTranslation(\AppBundle\Entity\Product\Translation $translation)
    {
        return $this->translations->removeElement($translation);
    }

    /**
     * Add image.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Product
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
