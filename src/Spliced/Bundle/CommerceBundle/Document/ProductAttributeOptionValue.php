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
use Spliced\Component\Commerce\Model\ProductAttributeOptionValue as BaseProductAttributeOptionValue;

/**
 * @MongoDB\EmbeddedDocument()
 */
class ProductAttributeOptionValue extends BaseProductAttributeOptionValue
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
     * @MongoDB\Collection
     */
    protected $productsToAdd;
    
    /**
     * @MongoDB\Hash
     */
    protected $valueData = array();
}