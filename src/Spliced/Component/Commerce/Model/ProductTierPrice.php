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

/**
 * ProductTierPrice
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductTierPrice
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
	/** 
	 * @MongoDB\Int 
	 */
	protected $minQuantity;
	
	/** 
	 * @MongoDB\Int 
	 */
	protected $maxQuantity;
	
	/** 
	 * @MongoDB\Int 
	 */
	protected $adjustmentType;
	
	/** 
	 * @MongoDB\Int 
	 */
	protected $adjustment;
	
	/** 
	 * @MongoDB\Hash 
	 */
	protected $options;

	/**
	 * getId
	 */
	public function getId()
	{
	    return $this->id;
	}
	
	/**
	 * getMinQuantity
	 *
	 */
	public function getMinQuantity()
	{
		return $this->minQuantity;
	}
	
	/**
	 * setMinQuantity
	 *
	 * @param int $value
	 */
	public function setMinQuantity($value)
	{
		$this->minQuantity = $value;
	
		return $this;
	}
	
	/**
	 * getMaxQuantity
	 *
	 */
	public function getMaxQuantity()
	{
		return $this->maxQuantity;
	}
	
	/**
	 * setMaxQuantity
	 *
	 * @param int $value
	 */
	public function setMaxQuantity($value)
	{
		$this->maxQuantity = $value;
	
		return $this;
	}
	
	/**
	 * getAdjustmentType
	 *
	 */
	public function getAdjustmentType()
	{
		return $this->adjustmentType;
	}
	
	/**
	 * setAdjustmentType
	 *
	 * @param string $value
	 */
	public function setAdjustmentType($value)
	{
		$this->adjustmentType = $value;
	
		return $this;
	}
	
	/**
	 * getAdjustment
	 *
	 */
	public function getAdjustment()
	{
		return $this->adjustment;
	}
	
	/**
	 * setAdjustment
	 *
	 * @param float $value
	 */
	public function setAdjustment($value)
	{
		$this->adjustment = $value;
	
		return $this;
	}
	
	/**
	 * setOptions
	 *
	 * @param array|null $options
	 */
	public function setOptions(array $options = null)
	{
		$this->options = $options;
		return $this;
	}
	
	/**
	 * getOptions
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}
}