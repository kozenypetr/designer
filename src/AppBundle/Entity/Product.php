<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

use AppBundle\Entity\Category;
use AppBundle\Entity\Image;
use AppBundle\Entity\Attribute;
use AppBundle\Entity\Material;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
    private $isActive = true;

    /**
     * Novinka
     * @var boolean
     *
     * @ORM\Column(name="is_new", type="boolean", options={"default": false})
     */
    private $isNew = false;

    /**
     * Doporucujeme
     * @var boolean
     *
     * @ORM\Column(name="is_top", type="boolean", options={"default": false})
     */
    private $isTop = false;


    /**
     * Nejprodavanejsi
     * @var boolean
     *
     * @ORM\Column(name="is_bestseller", type="boolean", options={"default": false})
     */
    private $isBestseller = false;


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
     * @ORM\Column(name="subname", type="string", length=255, nullable=true)
     */
    private $subname;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_metatitle", type="string", length=255, nullable=true)
     */
    private $customMetatitle;


    /**
     * @var string
     *
     * @ORM\Column(name="custom_metadescription", type="text", nullable=true, nullable=true)
     */
    private $customMetadescription;


    /**
     * @var string
     *
     * @ORM\Column(name="custom_metakeywords", type="string", length=255, nullable=true)
     */
    private $customMetakeywords;


    /**
     * @var json
     *
     * @ORM\Column(name="parameters", type="json", nullable=true)
     */
    private $parameters;


    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="feed_name", type="string", length=255, nullable=true)
     */
    private $feedName;

    /**
     * @var string
     *
     * @ORM\Column(name="feed_description", type="string", length=255, nullable=true)
     */
    private $feedDescription;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_heureka", type="boolean", options={"default": true})
     */
    private $isHeureka = true;

    /**
     * @var string
     *
     * @ORM\Column(name="heureka_category", type="string", length=255, nullable=true)
     */
    private $heurekaCategory;

    /**
     * @var integer
     *
     * @ORM\Column(name="heureka_cpc", type="integer", nullable=true)
     */
    private $heurekaCpc = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_zbozi", type="boolean", options={"default": true})
     */
    private $isZbozi = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="zbozi_max_cpc", type="integer", nullable=true)
     */
    private $zboziMaxCpc = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="zbozi_max_cpc_list", type="integer", nullable=true)
     */
    private $zboziMaxCpcList = null;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_google", type="boolean", options={"default": true})
     */
    private $isGoogle = true;

    /**
     * @var string
     *
     * @ORM\Column(name="google_product_category", type="string", length=255, nullable=true)
     */
    private $googleProductCategory;


    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=255, nullable=true)
     */
    private $model;

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
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products")
     * @ORM\JoinTable(
     *      name="product_category",
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="products")
     * @ORM\JoinTable(
     *      name="product_event",
     *      joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $events;


    /**
     * Many Products have One Category.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="mainProducts")
     * @ORM\JoinColumn(name="main_category_id", referencedColumnName="id")
     */
    private $mainCategory;


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
     * @ORM\Column(name="old_price", type="decimal", precision=15, scale=4, nullable=true)
     */
    private $oldPrice;

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
     * Options
     *
     * @var AttributeOption
     * @ORM\OneToMany(targetEntity="Attribute", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $attributes;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=true, options={"default": 1000})
     */
    private $sort = 1000;

    /**
     * @var integer
     *
     * @ORM\Column(name="availability", type="integer", nullable=true, options={"default": 14})
     */
    private $availability = 14;

    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=255, nullable=true)
     */
    private $module;


    /**
     * Many Products have One Material.
     * @ORM\ManyToOne(targetEntity="Material", inversedBy="products")
     * @ORM\JoinColumn(name="material_id", referencedColumnName="id")
     */
    private $material;

    // === add methods ===
    /**
     * @ORM\PrePersist
     */
    public function createHash()
    {
        $hash = md5(rand(0, 1000000) . $this->getName() . 'as34dtsd4s');
        $this->setHash($hash);
    }


    public function __construct()
    {
      $this->categories = new ArrayCollection();
      $this->images     = new ArrayCollection();
      $this->attributes = new ArrayCollection();
    }


    // === generated methods ===
    public function getFinalFeedName()
    {
        return $this->getFeedName()?$this->getFeedName():$this->getName();
    }

    public function __clone() {
        if ($this->getAttributes()->count())
        {
            $attributes = $this->getAttributes();

            $this->attributes = new ArrayCollection();

            foreach ($attributes as $attribute)
            {
                $a = clone $attribute;
                $a->setProduct($this);
                $this->addAttribute($a);
            }
        }

        if ($this->getImages()->count())
        {
            $images = $this->getImages();

            $this->images = new ArrayCollection();

            foreach ($images as $image)
            {
                $i = clone $image;
                $i->setProduct($this);
                $this->addImage($i);
            }
        }
    }

    public function __toString() {
        return $this->name?$this->name:'produkt';
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
     * @return Product
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
     * @return Product
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
     * @return Product
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
     * Set slug.
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
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set feedName.
     *
     * @param string $feedName
     *
     * @return Product
     */
    public function setFeedName($feedName)
    {
        $this->feedName = $feedName;

        return $this;
    }

    /**
     * Get feedName.
     *
     * @return string
     */
    public function getFeedName()
    {
        return $this->feedName;
    }

    /**
     * Set model.
     *
     * @param string $model
     *
     * @return Product
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set annotation.
     *
     * @param string|null $annotation
     *
     * @return Product
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
     * Set description.
     *
     * @param string|null $description
     *
     * @return Product
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
     * Set price.
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
     * Get price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price1.
     *
     * @param string|null $price1
     *
     * @return Product
     */
    public function setPrice1($price1 = null)
    {
        $this->price1 = $price1;

        return $this;
    }

    /**
     * Get price1.
     *
     * @return string|null
     */
    public function getPrice1()
    {
        return $this->price1;
    }

    /**
     * Set price2.
     *
     * @param string|null $price2
     *
     * @return Product
     */
    public function setPrice2($price2 = null)
    {
        $this->price2 = $price2;

        return $this;
    }

    /**
     * Get price2.
     *
     * @return string|null
     */
    public function getPrice2()
    {
        return $this->price2;
    }

    /**
     * Set price3.
     *
     * @param string|null $price3
     *
     * @return Product
     */
    public function setPrice3($price3 = null)
    {
        $this->price3 = $price3;

        return $this;
    }

    /**
     * Get price3.
     *
     * @return string|null
     */
    public function getPrice3()
    {
        return $this->price3;
    }

    /**
     * Set price4.
     *
     * @param string|null $price4
     *
     * @return Product
     */
    public function setPrice4($price4 = null)
    {
        $this->price4 = $price4;

        return $this;
    }

    /**
     * Get price4.
     *
     * @return string|null
     */
    public function getPrice4()
    {
        return $this->price4;
    }

    /**
     * Set sort.
     *
     * @param int|null $sort
     *
     * @return Product
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
     * Add category.
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
     * Remove category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        return $this->categories->removeElement($category);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set mainCategory.
     *
     * @param \AppBundle\Entity\Category|null $mainCategory
     *
     * @return Product
     */
    public function setMainCategory(\AppBundle\Entity\Category $mainCategory = null)
    {
        $this->mainCategory = $mainCategory;

        return $this;
    }

    /**
     * Get mainCategory.
     *
     * @return \AppBundle\Entity\Category|null
     */
    public function getMainCategory()
    {
        return $this->mainCategory;
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

    /**
     * Set attributes.
     *
     * @param string|null $attributes
     *
     * @return Product
     */
    public function setAttributes($attributes = null)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return string|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set parameters.
     *
     * @param string|null $parameters
     *
     * @return Product
     */
    public function setParameters($parameters = null)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameters.
     *
     * @return string|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Add attribute.
     *
     * @param \AppBundle\Entity\Attribute $attribute
     *
     * @return Product
     */
    public function addAttribute(\AppBundle\Entity\Attribute $attribute)
    {
        $attribute->setProduct($this);

        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute.
     *
     * @param \AppBundle\Entity\Attribute $attribute
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAttribute(\AppBundle\Entity\Attribute $attribute)
    {
        return $this->attributes->removeElement($attribute);
    }

    /**
     * Set module.
     *
     * @param string|null $module
     *
     * @return Product
     */
    public function setModule($module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module.
     *
     * @return string|null
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set subname.
     *
     * @param string|null $subname
     *
     * @return Product
     */
    public function setSubname($subname = null)
    {
        $this->subname = $subname;

        return $this;
    }

    /**
     * Get subname.
     *
     * @return string|null
     */
    public function getSubname()
    {
        return $this->subname;
    }

    /**
     * Set customMetatitle.
     *
     * @param string|null $customMetatitle
     *
     * @return Product
     */
    public function setCustomMetatitle($customMetatitle = null)
    {
        $this->customMetatitle = $customMetatitle;

        return $this;
    }

    /**
     * Get customMetatitle.
     *
     * @return string|null
     */
    public function getCustomMetatitle()
    {
        return $this->customMetatitle;
    }

    /**
     * Set customMetadescription.
     *
     * @param string|null $customMetadescription
     *
     * @return Product
     */
    public function setCustomMetadescription($customMetadescription = null)
    {
        $this->customMetadescription = $customMetadescription;

        return $this;
    }

    /**
     * Get customMetadescription.
     *
     * @return string|null
     */
    public function getCustomMetadescription()
    {
        return $this->customMetadescription;
    }

    /**
     * Set customMetakeywords.
     *
     * @param string|null $customMetakeywords
     *
     * @return Product
     */
    public function setCustomMetakeywords($customMetakeywords = null)
    {
        $this->customMetakeywords = $customMetakeywords;

        return $this;
    }

    /**
     * Get customMetakeywords.
     *
     * @return string|null
     */
    public function getCustomMetakeywords()
    {
        return $this->customMetakeywords;
    }

    /**
     * Set availability.
     *
     * @param int|null $availability
     *
     * @return Product
     */
    public function setAvailability($availability = null)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability.
     *
     * @return int|null
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set isNew.
     *
     * @param bool $isNew
     *
     * @return Product
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew.
     *
     * @return bool
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set isTop.
     *
     * @param bool $isTop
     *
     * @return Product
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;

        return $this;
    }

    /**
     * Get isTop.
     *
     * @return bool
     */
    public function getIsTop()
    {
        return $this->isTop;
    }

    /**
     * Set material.
     *
     * @param \AppBundle\Entity\Material|null $material
     *
     * @return Product
     */
    public function setMaterial(\AppBundle\Entity\Material $material = null)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material.
     *
     * @return \AppBundle\Entity\Material|null
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Add event.
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return Product
     */
    public function addEvent(\AppBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event.
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEvent(\AppBundle\Entity\Event $event)
    {
        return $this->events->removeElement($event);
    }

    /**
     * Get events.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set oldPrice.
     *
     * @param string|null $oldPrice
     *
     * @return Product
     */
    public function setOldPrice($oldPrice = null)
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    /**
     * Get oldPrice.
     *
     * @return string|null
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * Set feedDescription.
     *
     * @param string|null $feedDescription
     *
     * @return Product
     */
    public function setFeedDescription($feedDescription = null)
    {
        $this->feedDescription = $feedDescription;

        return $this;
    }

    /**
     * Get feedDescription.
     *
     * @return string|null
     */
    public function getFeedDescription()
    {
        return $this->feedDescription;
    }

    /**
     * Set isHeureka.
     *
     * @param bool $isHeureka
     *
     * @return Product
     */
    public function setIsHeureka($isHeureka)
    {
        $this->isHeureka = $isHeureka;

        return $this;
    }

    /**
     * Get isHeureka.
     *
     * @return bool
     */
    public function getIsHeureka()
    {
        return $this->isHeureka;
    }

    /**
     * Set heurekaCategory.
     *
     * @param string|null $heurekaCategory
     *
     * @return Product
     */
    public function setHeurekaCategory($heurekaCategory = null)
    {
        $this->heurekaCategory = $heurekaCategory;

        return $this;
    }

    /**
     * Get heurekaCategory.
     *
     * @return string|null
     */
    public function getHeurekaCategory()
    {
        return $this->heurekaCategory;
    }

    /**
     * Set heurekaCpc.
     *
     * @param int|null $heurekaCpc
     *
     * @return Product
     */
    public function setHeurekaCpc($heurekaCpc = null)
    {
        $this->heurekaCpc = $heurekaCpc;

        return $this;
    }

    /**
     * Get heurekaCpc.
     *
     * @return int|null
     */
    public function getHeurekaCpc()
    {
        return $this->heurekaCpc;
    }

    /**
     * Set isZbozi.
     *
     * @param bool $isZbozi
     *
     * @return Product
     */
    public function setIsZbozi($isZbozi)
    {
        $this->isZbozi = $isZbozi;

        return $this;
    }

    /**
     * Get isZbozi.
     *
     * @return bool
     */
    public function getIsZbozi()
    {
        return $this->isZbozi;
    }

    /**
     * Set zboziMaxCpc.
     *
     * @param int|null $zboziMaxCpc
     *
     * @return Product
     */
    public function setZboziMaxCpc($zboziMaxCpc = null)
    {
        $this->zboziMaxCpc = $zboziMaxCpc;

        return $this;
    }

    /**
     * Get zboziMaxCpc.
     *
     * @return int|null
     */
    public function getZboziMaxCpc()
    {
        return $this->zboziMaxCpc;
    }

    /**
     * Set zboziMaxCpcList.
     *
     * @param int|null $zboziMaxCpcList
     *
     * @return Product
     */
    public function setZboziMaxCpcList($zboziMaxCpcList = null)
    {
        $this->zboziMaxCpcList = $zboziMaxCpcList;

        return $this;
    }

    /**
     * Get zboziMaxCpcList.
     *
     * @return int|null
     */
    public function getZboziMaxCpcList()
    {
        return $this->zboziMaxCpcList;
    }

    /**
     * Set isGoogle.
     *
     * @param bool $isGoogle
     *
     * @return Product
     */
    public function setIsGoogle($isGoogle)
    {
        $this->isGoogle = $isGoogle;

        return $this;
    }

    /**
     * Get isGoogle.
     *
     * @return bool
     */
    public function getIsGoogle()
    {
        return $this->isGoogle;
    }

    /**
     * Set googleProductCategory.
     *
     * @param string|null $googleProductCategory
     *
     * @return Product
     */
    public function setGoogleProductCategory($googleProductCategory = null)
    {
        $this->googleProductCategory = $googleProductCategory;

        return $this;
    }

    /**
     * Get googleProductCategory.
     *
     * @return string|null
     */
    public function getGoogleProductCategory()
    {
        return $this->googleProductCategory;
    }

    /**
     * Set isBestseller.
     *
     * @param bool $isBestseller
     *
     * @return Product
     */
    public function setIsBestseller($isBestseller)
    {
        $this->isBestseller = $isBestseller;

        return $this;
    }

    /**
     * Get isBestseller.
     *
     * @return bool
     */
    public function getIsBestseller()
    {
        return $this->isBestseller;
    }

    /**
     * Set hash.
     *
     * @param string|null $hash
     *
     * @return Product
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
