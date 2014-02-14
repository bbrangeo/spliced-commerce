<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Product
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="product")
 */
abstract class Product implements ProductInterface
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected $sku;

    /**
     * @MongoDB\Int
     */
    protected $type;
    
    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(order="asc")
     */
    protected $urlSlug;

    /**
     * @MongoDB\Boolean
     */
    protected $manageStock;

    /**
     * @MongoDB\Float
     * @MongoDB\Index()
     */
    protected $price;

    /**
     * @MongoDB\Float
     * @MongoDB\Index()
     */
    protected $cost;

    /**
     * @MongoDB\Float
     * @MongoDB\Index()
     */
    protected $specialPrice;

    /**
     * @MongoDB\Boolean
     */
    protected $isTaxable;

    /**
     * @MongoDB\Boolean
     * @MongoDB\Index()
     */
    protected $isActive;
    
    /**
     * @MongoDB\Int
     * @MongoDB\Index()
     */
    protected $availability;
    
    /**
     * @MongoDB\Int
     */
    protected $minOrderQuantity;

    /**
     * @MongoDB\Hash
     */
    protected $weight;
    
    /**
     * @MongoDB\Hash
     */
    protected $dimensions;
    
    /**
     * @MongoDB\Int
     */
    protected $maxOrderQuantity;
    
    /**
     * @MongoDB\Date
     */
    protected $createdAt;

    /**
     * @MongoDB\Date
     */
    protected $updatedAt;
    
    /**
     * @MongoDB\Date(nullable=true)
     */
    protected $specialFromDate;

    /**
     * @MongoDB\Date(nullable=true)
     */
    protected $specialToDate;
    
    /**
     * Content of the product, by language
     * 
     * @MongoDB\EmbedMany(targetDocument="ProductContent")
     */
    protected $content = array();
    
    /**
     * @MongoDB\EmbedMany(targetDocument="ProductAttribute")
     */
    protected $attributes = array();

    /**
     * @MongoDB\EmbedMany(targetDocument="ProductSpecification")
     * @MongoDB\Index()
     */
    protected $specifications = array();
    
    /**
     * @MongoDB\EmbedMany(targetDocument="ProductBundledItem")
     */
    protected $bundledItems = array();
    
    /**
     * @MongoDB\EmbedMany(targetDocument="ProductImage")
     */
    protected $images = array();
    
    /**
     * @MongoDB\EmbedMany(targetDocument="ProductTierPrice")
     */
    protected $tierPrices;
    
    /**
     * @MongoDB\ReferenceMany(targetDocument="Route")
     */
    protected $routes;
    
    /**
     * @MongoDB\EmbedMany(targetDocument="ProductUpsale")
     */
    protected $upsales;
    
    /** 
     * @MongoDB\ReferenceMany(targetDocument="Category")
     */
    protected $categories;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Manufacturer")
     */
    protected $manufacturer;
    
    /**
     * @MongoDB\String
     */
    protected $manufacturerPart;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->type = ProductInterface::TYPE_PHYSICAL;
        $this->availability = ProductInterface::AVAILABILITY_IN_STOCK;
        $this->minOrderQuantity = 1;
        $this->manageStock = false;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        
        $this->content = new ArrayCollection();
        $this->attributes = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->bundledItems = new ArrayCollection();
        $this->tierPrices = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->upsales = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->routes = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * setSku
     *
     * @param string $sku
     *
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    
        return $this;
    }
    
    /**
     * getSku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }
    
    /**
     * getType
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * setType
     * 
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * setName
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
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setUrlSlug
     *
     * @param string $urlSlug
     *
     * @return Product
     */
    public function setUrlSlug($urlSlug)
    {
        $this->urlSlug = $urlSlug;

        return $this;
    }

    /**
     * getUrlSlug
     *
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * Set manageStock
     *
     * @param boolean $manageStock
     */
    public function setManageStock($manageStock)
    {
        $this->manageStock = $manageStock;

        return $this;
    }

    /**
     * getUrlSlug
     *
     * @return boolean
     */
    public function getManageStock()
    {
        return $this->manageStock;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * getPrice
     *
     * @return decimal
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set specialPrice
     *
     * @param decimal $specialPrice
     */
    public function setSpecialPrice($specialPrice)
    {
        $this->specialPrice = $specialPrice;

        return $this;
    }

    /**
     * getSpecialPrice
     *
     * @return decimal
     */
    public function getSpecialPrice()
    {
        return $this->specialPrice;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
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
     * Set specialFromDate
     *
     * @param datetime $specialFromDate
     */
    public function setSpecialFromDate(\DateTime $specialFromDate = null)
    {
        $this->specialFromDate = $specialFromDate;

        return $this;
    }

    /**
     * Get specialFromDate
     *
     * @return datetime
     */
    public function getSpecialFromDate()
    {
        return $this->specialFromDate;
    }

    /**
     * Set specialToDate
     *
     * @param datetime $specialToDate
     */
    public function setSpecialToDate($specialToDate)
    {
        $this->specialToDate = $specialToDate;

        return $this;
    }

    /**
     * Get specialToDate
     *
     * @return datetime
     */
    public function getSpecialToDate()
    {
        return $this->specialToDate;
    }


    /**
     * getCost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }
    
    /**
     * setCost
     *
     * @param float $value
     */
    public function setCost($value = null)
    {
        $this->cost = $value;
    
        return $this;
    }
    
    /**
     * getIsTaxable
     *
     * @return bool
     */
    public function getIsTaxable()
    {
        return $this->isTaxable;
    }
    
    /**
     * setIsTaxable
     *
     * @param bool $value
     */
    public function setIsTaxable($value = null)
    {
        $this->isTaxable = $value;
    
        return $this;
    }
    
    
    
    /**
     * getAvailability
     *
     * @return string
     */
    public function getAvailability()
    {
        return $this->availability;
    }
    
    /**
     * setAvailability
     *
     * @param string $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
        return $this;
    }

    /**
     * getAvailabilityName
     *
     * @return string
     */
    public function getAvailabilityName()
    {
    
        switch($this->getAvailability()){
            default:
            case ProductInterface::AVAILABILITY_IN_STOCK:
                return 'In Stock';
                break;
            case ProductInterface::AVAILABILITY_UNAVAILABLE:
                return 'Unavailable';
                break;
            case ProductInterface::AVAILABILITY_BACKORDERED:
                return 'Backordered';
                break;
            case ProductInterface::AVAILABILITY_CALL_TO_ORDER:
                return 'Call to Order';
                break;
            case ProductInterface::AVAILABILITY_BUILT_TO_ORDER:
                return 'Built to Order';
                break;
    
        }
    }
    
    /**
     * getMaxOrderQuantity
     *
     * @return int|null
     */
    public function getMaxOrderQuantity()
    {
        return $this->maxOrderQuantity;
    }
    
    /**
     * setMaxOrderQuantity
     *
     * @param int $maxOrderQuantity
     */
    public function setMaxOrderQuantity($maxOrderQuantity = null)
    {
        $this->maxOrderQuantity = $maxOrderQuantity;
        return $this;
    }
    
    /**
     * getMinOrderQuantity
     *
     * @return int|null
     */
    public function getMinOrderQuantity()
    {
        return $this->minOrderQuantity;
    }
    
    /**
     * setMinOrderQuantity
     *
     * @param int $minOrderQuantity
     */
    public function setMinOrderQuantity($minOrderQuantity = null)
    {
        $this->minOrderQuantity = $minOrderQuantity;
        return $this;
    }
    


    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }
    
    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }
    
    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    
    /**
     * getContent
     *
     * @return array|Collection
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * setContent
     * 
     * @param array|Collection
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * setContent
     *
     * @param ProductContent $content
     */
    public function addContent(ProductContent $content)
    {
        if(!$this->content->contains($content)){
    		$this->content->add($content);
    	}
        return $this;
    }
    
    /**
     * removeContent
     *
     * @param ProductContent $content
     */
    public function removeContent(ProductContent $content)
    {
        $this->content->removeElement($content);
        return $this;
    }
    
    /**
     * getContentByLanguage
     * 
     * @param string $language
     */
    public function getContentByLanguage($language = 'en')
    {
        foreach($this->getContent() as $content){
            if($language == $content->getLanguage()){
                return $content;
            }
        }
        return false;
    }
    

    /**
     * setAttributes
     */
    public function setAttributes($attributes){
        if(is_array($attributes)){
            $this->attributes = new ArrayCollection($attributes);
        } else if($attributes instanceof Collection){
            $this->attributes = $attributes;
        } else {
            throw new \Exception("Attributes Must be Array or ArrayCollection");
        }
        return $this;
    }
    
    /**
     * Get attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    
    
    /**
     * hasAttribute
     *
     * @param string $name
     */
    public function hasAttribute($name)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getOption()->getName() == $name) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * getAttribute
     *
     * @param string $name
     */
    public function getAttribute($name)
    {
        $return = new ArrayCollection();
    
        foreach ($this->attributes as $attribute) {
            $attributeOption = $attribute->getOption();
            if ($attributeOption->getName() == $name) {
                if ( $attributeOption->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_SINGLE_VALUE ) {
                    return $attribute;
                } elseif ( $attributeOption->getOptionType() == ProductAttributeOptionInterface::OPTION_TYPE_MULTIPLE_VALUES ) {
                    $return->add($attribute);
                } else {
                    return $attribute;
                }
            }
        }
    
        return $return->count() ? $return : false;
    }
    
    /**
     * removeAttribute
     *
     * @param string $key
     */
    public function removeAttribute(ProductAttribute $productAttribute)
    {
        $this->attributes->removeElement($productAttribute);
        return $this;
    }
    
    /**
     * Add attribute
     *
     * @return Product
     */
    public function addAttribute(ProductAttribute $attribute)
    {
        if(!$this->attributes->contains($attribute)){
        	$this->attributes->add($attribute);
        }
    
        return $this;
    }
    
    

    /**
     * setSpecifications
     */
    public function setSpecifications($specifications){
        if(is_array($specifications)){
            $this->specifications = new ArrayCollection($specifications);
        } else if($specifications instanceof Collection){
            $this->specifications = $specifications;
        } else {
            throw new \Exception("Specifications Must be Array or ArrayCollection");
        }
        return $this;
    }
    
    /**
     * getSpecifications
     * 
     * @return Collection
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }

    
    /**
     * removeSpecification
     *
     * @param ProductSpecification $productSpecification
     */
    public function removeSpecification(ProductSpecification $productSpecification)
    {
        $this->specifications->removeElement($productSpecification);
        return $this;
    }
    
    /**
     * addSpecification
     *
     * @return Product
     */
    public function addSpecification(ProductSpecification $specification)
    {
        if(!$this->specifications->contains($specification)){
            $this->specifications->add($specification);
        }
        return $this;
    }

    
    /**
     * getInformationalAttributes
     *
     * @return Collection
     */
    public function getInformationalAttributes()
    {
        $return = new ArrayCollection();
        foreach($this->getAttributes() as $attribute){
            if(in_array($attribute->getOptionType(), array(1,2))){
                $return->add($attribute);
            }
        }
        return $return;
    }

    /**
     * hasPriceAlteringAttributes
     *
     * @return bool
     */
    public function hasPriceAlteringAttributes()
    {
        if(!$this->getAttributes()){
            return false;
        }
        foreach($this->getAttributes() as $attr){
            if($attr->getOption()->getOptionType() == 3){
                foreach($attr->getOption()->getValues() as $value){
                    if($value->getPriceAdjustmentType()){
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * getPriceAlteringAttributes
     *
     * @return Collection
     */
    public function getPriceAlteringAttributes()
    {
        $return = new ArrayCollection();
        foreach($this->getAttributes() as $attribute){
            if($attribute->getOption()->getOptionType() == 3){
                $return->add($attribute);
            }
        }
        return $return;
    }
     
    /**
     *
     */
    public function hasUserDataAttributes()
    {
        foreach($this->attributes as $attr){
            if($attr->getOption()->getOptionType() == 3 || $attr->getOption()->getOptionType() == 4){
                return true;
            }
        }
        return false;
    }
    
    /**
     * getUserDataAttributes
     *
     * @return array
     */
    public function getUserDataAttributes()
    {
        $return = array();
        foreach($this->attributes as $attr) {
            if($attr->getOption()->getOptionType() == 3 || $attr->getOption()->getOptionType() == 4){
                $return[] = $attr;
            }
        }
        return $return;
    }
    
    /**
     * setBundledItems
     *
     * @param Collection $bundledItems
     */
    public function setBundledItems(Collection $bundledItems)
    {
        $this->bundledItems = $bundledItems;
        return $this;
    }
    
    /**
     * addBundledItem
     *
     * @param ProductBundledItem $item
     */
    public function addBundledItem(ProductBundledItem $item)
    {
    	if(!$this->bundledItems->contains($item)){
    		$this->bundledItems->add($item);
    	}
        return $this;
    }
    
    /**
     * getBundledItems
     *
     * @return Collection
     */
    public function getBundledItems()
    {
        return $this->bundledItems;
    }
    
    /**
     * removeBundledItem
     *
     * @param ProductBundledItem $bundledItem
     */
    public function removeBundledItem(ProductBundledItem $bundledItem)
    {
        $this->bundledItems->removeElement($bundledItem);
        return $this;
    }
    

    /**
     * getImages
     *
     */
    public function getImages()
    {
        return $this->images;
    }
         
    /**
     * setImages
     *
     * @param Collection $images
     */
    public function setImages(Collection $images)
    {
        $this->images = $images;
        return $this;
    }
    
    /**
     * removeImage
     *
     * @return Product
     */
    public function removeImage(ProductImage $image)
    {
        $this->images->removeElement($image);
        return $this;
    }
    
    /**
     * Add images
     *
     * @return Product
     */
    public function addImage(ProductImage $image)
    {
    	if(!$this->images->contains($image)){
    		$this->images->add($image);
    	}
        return $this;
    }
    /**
     * getMainImage
     *
     * Gets the first image in the collection marked as main
     * or the first image otherwise, null in the case of no images
     *
     * @return ProductImage|null
     */
    public function getMainImage()
    {
        foreach($this->getImages() as $image){
            if($image->getIsMain()){
                return $image;
            }
        }
        return $this->getImages()->first();
    }
    

    /**
     * getTierPrices
     * 
     * @return Collection
     */
    public function getTierPrices()
    {
        return $this->tierPrices;
         
    }
    
    /**
     * setTierPrices
     *
     * @param Collection $tierPrices
     */
    public function setTierPrices(Collection $tierPrices)
    {
        $this->tierPrices = $tierPrices;
        return $this;
    }
    
    /**
     * addTierPrice
     *
     * @param ProductTierPrice $tierPrice
     */
    public function addTierPrice(ProductTierPrice $tierPrice)
    {
    	if(!$this->tierPrices->contains($tierPrice)){
    		$this->tierPrices->add($tierPrice);
    	}
    	        
        return $this;
    }
    
    /**
     * removeTierPrice
     *
     * @param ProductTierPrice $tierPrice
     */
    public function removeTierPrice(ProductTierPrice $tierPrice)
    {
        $this->tierPrices->removeElement($tierPrice);
        return $this;
    }

    
    /**
     * setRoutes
     *
     * @param Collection $routes
     */
    public function setRoutes(Collection $routes)
    {
        $this->routes = $routes;    
        return $this;
    }
    
    /**
     * getRoutes
     *
     * @return Collection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * addRoute
     * 
     * @param RouteInterface $route
     */
    public function addRoute(RouteInterface $route)
    {
        if (!$this->routes->contains($route)) {
            $this->routes->add($route);
        }
        return $this;
    }
    
    /**
     * removeRoute
     *
     * @param RouteInterface $route
     */
    public function removeRoute(RouteInterface $route)
    {
        $this->routes->removeElement($route);
        return $this;
    }
    
    /**
     * setUpsales
     *
     * @param Collection $upsales
     */
    public function setUpsales(Collection $upsales)
    {
        $this->upsales = $upsales;
        return $this;
    }
    
    /**
     * addUpsale
     *
     * @param ProductInterface $upsale
     */
    public function addUpsale(ProductUpsale $upsale)
    {
        if(!$this->upsales->contains($upsale)){
        	$this->upsales->add($upsale);
        }
        return $this;
    }
        
    /**
     * getUpsales
     *
     * @return Collection
     */
    public function getUpsales()
    {
        return $this->upsales;
    }
    
    /**
     * removeUpsale
     *
     * @param ProductInterface $upsale
     */
    public function removeUpsale(ProductUpsale $upsale)
    {
        $this->upsales->removeElement($upsale);
        return $this;
    }
    

    /**
     * Get categories
     *
     * @param Category $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * set categories
     *
     * @param Category $categories
     */
    public function setCategories($categories)
    {
        if(is_array($categories)){
            $this->categories = new ArrayCollection($categories);
        } else if($categories instanceof ArrayCollection){
            $this->categories = $categories;
        } else {
            throw new \Exception("Categories Must be Array or ArrayCollection");
        }
         
        return $this;
    }
    /**
     * addCategory
     *
     * @param CategoryInterface $category
     *
     * @return Product
     */
    public function addCategory(CategoryInterface $category )
    {
        $this->categories->add($category);
        return $this;
    }
     
    /**
     * hasCategory
     *
     * @param CategoryInterface $category
     *
     * @return Product
     */
    public function hasCategory(CategoryInterface $category)
    {
        foreach($this->getCategories() as $_category){
            if($_category->getId() && $_category->getId() == $category->getId()){
                return true;
            }
        }
        return false;
    }
    


    /**
     * getManufacturer
     * 
     * @return ManufacturerInterface
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }
    
    /**
     * setManufacturer
     *
     * @return ManufacturerInterface
     */
    public function setManufacturer(ManufacturerInterface $manufacturer = null)
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }
    
    /**
     * setManufacturerPart
     *
     * @param string $manufacturerPart
     *
     * @return Product
     */
    public function setManufacturerPart($manufacturerPart)
    {
        $this->manufacturerPart = $manufacturerPart;
    
        return $this;
    }
    
    /**
     * getManufacturerPart
     *
     * @return string
     */
    public function getManufacturerPart()
    {
        return $this->manufacturerPart;
    }
    

    /**
     * getWeight
     */
    public function getWeight()
    {
        return $this->weight;
    }
    
    /**
     * setWeight
     *
     * @param array $weight
     */
    public function setWeight(array $weight = array())
    {
        $this->weight = $weight;
        return $this;
    }
    
    /**
     * getDimensions
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }
    
    /**
     * setDimensions
     *
     * @param array $dimensions
     */
    public function setDimensions(array $dimensions = array())
    {
        $this->dimensions = $dimensions;
        return $this;
    }

       
}