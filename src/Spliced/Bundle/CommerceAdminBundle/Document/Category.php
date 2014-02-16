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
use Spliced\Component\Commerce\Model\Category as BaseCategory;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document(
 *     collection="category",
 *  repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\CategoryDocumentRepository"
 * )
 * @MongoDB\EmbeddedDocument
 * @MongoDB\MappedSuperclass()
 * 
 * @Gedmo\Tree(type="materializedPath", activateLocking=true)
 */
class Category extends BaseCategory
{
    
    /**
     * @MongoDB\String
     * @Gedmo\TreePathSource
     */
    protected $name;
       
    /**
     * @MongoDB\Int
     * @Gedmo\TreeLevel
     */
    protected $level;
    
    /**
     * @MongoDB\String
     * @Gedmo\TreePath
     */
    protected $path;
    
    /**
     * @MongoDB\Int
     * @Gedmo\TreeLeft
     */
    protected $left;
    
    /**
     * @MongoDB\Int
     * @Gedmo\TreeRight
     */
    protected $right;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Category")
     * @MongoDB\Index
     * @Gedmo\TreeParent
     */
    protected $parent;
    
    /**
     * @Gedmo\TreeLockTime
     * @MongoDB\Date(nullable=true)
     */
    private $lockTime;
    

}