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
 * ProductUpsale
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\EmbeddedDocument()
 */
class ProductUpsale
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /** 
    * @MongoDB\ReferenceOne(targetDocument="Product") 
    */
    protected $product;
    
    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * getProduct
     * 
     * @return ProductInterface
     */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * setProduct
	 * 
	 * @param ProductInterface $product
	 */
	public function setProduct(ProductInterface $product)
	{
		$this->product = $product;
		return $this;
	}	
		
	
	
}