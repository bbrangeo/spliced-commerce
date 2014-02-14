<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Spliced\Component\Commerce\Product\TypeHandler\TypeHandlerInterface;

/**
 * ProductTypeManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductTypeManager
{
	/** @var ArrayCollection */
	protected $handlers;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->handlers = new ArrayCollection();
	}
	
	/**
	 * getHandlers
	 * 
	 * Returns a collection of registers type handlers
	 * 
	 * @return ArrayCollection
	 */
	public function getHandlers()
	{
		return $this->handlers;
	}
	
	/**
	 * addHandler
	 * 
	 * @param TypeHandlerInterface $handler
	 */
	 public function addHandler(TypeHandlerInterface $handler)
	 {
	 	$existingHandler = $this->getHandlerByTypeCode($handler->getTypeCode());
	 	
	 	if($existingHandler !== false){
	 		throw new \InvalidArgumentException(sprintf('Product Type With Type Code %s Already Defined Under %s',
				$handler->getTypeCode(),
				$existingHandler->getName()
			));
	 	}
		
	 	$existingHandler = $this->getHandlerByName($handler->getName());
	 	
	 	if($existingHandler !== false){
	 		throw new \InvalidArgumentException(sprintf('Product Type With Type Name %s Already Defined Under %s',
				$handler->getName(),
				get_class($existingHandler)
			));
	 	}
		
	 	$this->handlers->set($handler->getName(), $handler);
		
		return $this;
	 }
	 
	 /**
	  * hasHandlerByName
	  * 
	  * @param string $name
	  */
	  public function hasHandlerByName($name)
	  {
		foreach($this->handlers as $handler){
			if($handler->getName() === $name){
				return true;
			}
		}
		return false;
	  }
	  
	 /**
	  * getHandlerByName
	  * 
	  * @param string $name
	  */
	  public function getHandlerByName($name)
	  {
		foreach($this->handlers as $handler){
			if($handler->getName() === $name){
				return $handler;
			}
		}
		return false;
	  }
	  
	 /**
	  * hasHandlerByTypeCode
	  * 
	  * @param int $typeCode
	  */
	  public function hasHandlerByTypeCode($typeCode)
	  {
	  	
	  	$typeCode = (int) $typeCode;
		
		foreach($this->handlers as $handler){
			if($handler->getTypeCode() === $typeCode){
				return true;
			}
		}
		return false;
	  }
	  
	  /**
	   * getHandlerByTypeCode
	   * 
	   * @param int $typeCode
	   */
	   public function getHandlerByTypeCode($typeCode)
	   {
		foreach($this->handlers as $handler){
			if($handler->getTypeCode() === $typeCode){
				return $handler;
			}
		}
		return false;
	   }
}
