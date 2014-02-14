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
use Spliced\Component\Commerce\Model\ProductImage as BaseProductImage;

/**
 * @MongoDB\EmbeddedDocument()
 */
class ProductImage extends BaseProductImage
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\Boolean
     */
    protected $isMain;
    
    /**
     * @MongoDB\String
     */
    protected $fileName;
    
    /**
     * @MongoDB\String
     */
    protected $filePath;
    
    /**
     * @MongoDB\String
     */
    protected $fileMd5;
    
    /**
     * @MongoDB\String
     */
    protected $fileType;
    
}