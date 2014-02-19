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
use Spliced\Component\Commerce\Model\ProductSpecification as BaseProductSpecification;

/**
 * @MongoDB\EmbeddedDocument()
 */
class ProductSpecification extends BaseProductSpecification
{
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="ProductSpecificationOption")
     * @MongoDB\Index
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
     * @MongoDB\Index
     */
    protected $values = array();
}