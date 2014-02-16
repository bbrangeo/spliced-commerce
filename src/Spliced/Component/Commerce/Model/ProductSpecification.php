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
 * ProductSpecification
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\EmbeddedDocument()
 */
abstract class ProductSpecification
{    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="ProductSpecificationOption")
     */
    protected $option;
    
    /**
     * @MongoDB\String
     */
    protected $optionKey;
         
    /**
     * @MongoDB\Int
     */
    protected $optionType;
    
    /**
     * @MongoDB\Hash
     */
    protected $values = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = array();
    }
     
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @{inheritDoc}
     */
    public function setOption(ProductSpecificationOption $option)
    {
        $this->option = $option;
        $this->optionKey = $option->getKey();
        $this->optionType = $option->getOptionType();
        return $this;
    }

    /**
     * @{inheritDoc}
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * hasValue
     * 
     * @param string $value
     * 
     * @return bool
     */
    public function hasValue($value)
    {
        foreach($this->getValues() as $_value){
            if($_value === $value){
                return true;
            }
        }
        return false;
    }
    
    /**
     * @{inheritDoc}
     */
    public function addValue($value)
    {
        if(empty($value)){
            return $this;
        }
        
        if($this->hasValue($value)){
            return $this;
        }
        
        $this->values[] = $value;
        return $this;
    }
    
    /**
     * @{inheritDoc}
     */
    public function removeValue($value)
    {
        foreach($this->getValues() as $key => $_value){
            if($_value == $value){
                unset($this->values[$key]);
            }
        }
        return $this;
    }
        
    /**
     * @{inheritDoc}
     */
    public function setValues($values)
    {
        $this->values = $values;
        return $this;
    }
    
    /**
     * @{inheritDoc}
     */
    public function getValues()
    {
        return $this->values;
    }
    
    /**
     * getOptionKey
     * 
     * @return string
     */
    public function getOptionKey()
    {
        return $this->optionKey;
    }
    
    /**
     * setOptionKey
     * 
     * @param string $optionKey
     */
    public function setOptionKey($optionKey)
    {
        $this->optionKey = $optionKey;
        return $this;
    }

    /**
     * getOptionType
     * 
     * @return int
     */
    public function getOptionType()
    {
        return $this->optionKey;
    }
    
    /**
     * setOptionType
     * 
     * @param int $optionType
     */
    public function setOptionType($optionKey)
    {
        $this->optionKey = $optionKey;
        return $this;
    }

}