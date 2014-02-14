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
 * ProductAttributeOptionValue
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAttributeOptionValue
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\String
     */
    protected $value;

    /**
     * @MongoDB\String
     */
    protected $publicValue;
    
    /**
     * @MongoDB\Int
     */
    protected $position;

    /**
     * @MongoDB\Int
     */
    protected $priceAdjustment;

    /**
     * @MongoDB\Int
     */
    protected $priceAdjustmentType;

    /**
     * @MongoDB\EmbedMany(targetDocument="ProductAttributeOptionValueProduct")
     */
    protected $products;

    /**
     * @MongoDB\Hash
     */
    protected $valueData = array();
    
    /**
     * {@inheritDoc}
     */
    public function __construct(array $data = array())
    {
        $this->products = new ArrayCollection();
    }

    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return text
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set publicValue
     *
     * @param text $publicValue
     */
    public function setPublicValue($publicValue)
    {
        $this->publicValue = $publicValue;

        return $this;
    }

    /**
     * Get publicValue
     *
     * @return text
     */
    public function getPublicValue()
    {
        return $this->publicValue ? $this->publicValue : $this->value;
    }

    /**
     * setPosition
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * getPosition
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * getPriceAdjustment
     *
     * @return float
     */
    public function getPriceAdjustment()
    {
        return $this->priceAdjustment;
    }

    /**
     * setPriceAdjustment
     *
     * @param float $priceAdjustment
     */
    public function setPriceAdjustment($priceAdjustment)
    {
        $this->priceAdjustment = $priceAdjustment;
        return $this;
    }

    /**
     * getPriceAdjustmentType
     */
    public function getPriceAdjustmentType()
    {
        return $this->priceAdjustmentType;
    }

    /**
     * setPriceAdjustmentType
     */
    public function setPriceAdjustmentType($priceAdjustmentType)
    {
        $this->priceAdjustmentType = $priceAdjustmentType;
        return $this;
    }

    /**
     * getProducts
     * 
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
    
    
    /**
     * addProduct
     *
     * @param ProductInterface $product
     */
    public function addProduct(ProductAttributeOptionValueProduct $product)
    {
        if(!$this->products->contains($product)){
            $this->products->add($product);
        }
        return $this;
    }

    /**
     * removeProduct
     *
     * @param ProductAttributeOptionValueProduct $product
     */
    public function removeProduct(ProductAttributeOptionValueProduct $product)
    {
        $this->products->removeElement($product);
        return $this;
    }
    
    /**
     * setProductsToAdd
     * 
     * @param Collection $productsToAdd
     */
    public function setProducts(Collection $products)
    {
        $this->products = $products;
        return $this;
    }
    
    /**
     * setValueData
     *
     * @param array $valueData
     */
    public function setValueData(array $valueData)
    {
        $this->valueData = $valueData;
        return $this;
    }
    
    /**
     * getValueData
     *
     * @return array
     */
    public function getValueData()
    {
        return $this->valueData;
    }
}
