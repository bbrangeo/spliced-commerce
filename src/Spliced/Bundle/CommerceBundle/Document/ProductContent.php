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
use Spliced\Component\Commerce\Model\ProductContent as BaseProductContent;

/**
 * @MongoDB\EmbeddedDocument()
 */
class ProductContent extends BaseProductContent
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\String
     */
    protected $language;
    
    /**
     * @MongoDB\String
     */
    protected $name;
    
    /**
     * @MongoDB\String
     */
    protected $metaDescription;
    
    /**
     * @MongoDB\String
     */
    protected $metaKeywords;
    
    /**
     * @MongoDB\String
     */
    protected $longDescription;
    
    /**
     * @MongoDB\String
     */
    protected $shortDescription;
        
}