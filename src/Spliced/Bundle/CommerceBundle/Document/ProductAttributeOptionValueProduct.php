<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Spliced\Component\Commerce\Model\ProductAttributeOptionValueProduct as BaseProductAttributeOptionValueProduct;

/**
 * ProductAttributeOptionValueProduct
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\EmbeddedDocument()
 */
class ProductAttributeOptionValueProduct extends BaseProductAttributeOptionValueProduct
{
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Product")
     * @MongoDB\Index
     */
    protected $product;
     
    /**
     * @MongoDB\Float
     */
    protected $priceAdjustment;
    
    /**
     * @MongoDB\Int
     */
    protected $priceAdjustmentType;
    
    /**
     * @MongoDB\Int
     */
    protected $quantity;
    
    /**
     * @MongoDB\Boolean
     */
    protected $allowTierPricing;
}