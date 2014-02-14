<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Spliced\Component\Commerce\Model\ProductBundledItem as BaseProductBundledItem;

/**
 * ProductAttributeOptionValueProduct
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\EmbeddedDocument()
 */
class ProductBundledItem extends BaseProductBundledItem
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