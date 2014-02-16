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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Category
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="category")
 * @Gedmo\Tree(type="materializedPath", activateLocking=true)
 */
abstract class Category implements CategoryInterface
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Gedmo\TreePathSource
     */
    protected $name;

    /**
     * @MongoDB\String
     */
    protected $pageTitle;

    /**
     * @MongoDB\String
     */
    protected $description;

    /**
     * @MongoDB\String
     */
    protected $headTitle;

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
     * @MongoDB\UniqueIndex
     * @Gedmo\Slug(fields={"name"})
     */
    protected $urlSlug;

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
     * @MongoDB\Int
     */
    protected $position;

    /**
     * @MongoDB\Boolean
     */
    protected $isActive;
    
    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Category")
     * @MongoDB\Index
     * @Gedmo\TreeParent
     */
    protected $parent;
    
    /**
     * @MongoDB\ReferenceMany(
     *     targetDocument="Category",
     *     sort={"position": "asc", "name" : "asc"}
     * )
     * @MongoDB\Index
     */
    protected $children;

    /**
     * @Gedmo\TreeLockTime
     * @MongoDB\Date(nullable=true)
     */
    private $lockTime;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
        $date = new \DateTime('now');
        $this->setCreatedAt($date);
        $this->setUpdatedAt($date);
        $this->setIsActive(true);
        
    }
    
    /**
     * __toString
     */
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Get id
     *
     * @return bigint
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set parent_id
     *
     * @param bigint $parentId
     */
    public function setParent(CategoryInterface $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent_id
     *
     * @return bigint
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * setRoutes
     *
     * @param Collection $routes
     */
    public function setRoutes(Collection $routes)
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * getRoutes
     *
     */
    public function getRoutes()
    {
        return $this->route;
    }
    
    /**
     * Add child category
     *
     * @param CategoryInterface $child
     */
    public function addChild(CategoryInterface $child)
    {
        $this->children->add($child);
        return $this;
    }
    
    /**
     * hasChild
     *
     * @param CategoryInterface $child
     */
    public function hasChild(CategoryInterface $child)
    {
        return $this->children->has($child);
    }
    
    /**
     * getChild
     *
     * @param CategoryInterface $child
     */
    public function getChild(CategoryInterface $child)
    {
        return $this->children->get($child);
    }
        
    /**
     * getChildren
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * setChildren
     *
     * @param Collection|array
     */
    public function setChildren($children)
    {
        if(!$children instanceof Collection){
            if(is_array($children)){
                $children = new ArrayCollection($children);
            } else {
                throw new \InvalidArgumentException('Children must be an array or instanceof Doctrine\Common\Collections\Collection');
            }    
        }
        
        $this->children = $children;
        return $this;
    }
    
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pageTitle
     *
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get pageTitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set headTitle
     *
     * @param string $headTitle
     */
    public function setHeadTitle($headTitle)
    {
        $this->headTitle = $headTitle;

        return $this;
    }

    /**
     * Get headTitle
     *
     * @return string
     */
    public function getHeadTitle()
    {
        return $this->headTitle;
    }

    /**
     * Set metaDescription
     *
     * @param text $metaDescription
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return text
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param text $metaKeywords
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return text
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set urlSlug
     *
     * @param string $urlSlug
     */
    public function setUrlSlug($urlSlug)
    {
        $this->urlSlug = $urlSlug;

        return $this;
    }

    /**
     * Get urlSlug
     *
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    
    /**
     * getPath
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * setPath
     * 
     * @param string
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    /**
     * getLevel
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->setLevel;
    }
    
    /**
     * setLevel
     *
     * @param string
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }
    
    /**
     * getLeft
     *
     * @return string
     */
    public function getLeft()
    {
        return $this->setLeft;
    }
    
    /**
     * setLeft
     *
     * @param string
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }
    

    /**
     * getRight
     *
     * @return string
     */
    public function getRight()
    {
        return $this->setRight;
    }
    
    /**
     * setRight
     *
     * @param string
     */
    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }
    
    /**
     * getLockTime
     *
     * @return DateTime|null
     */
    public function getLockTime()
    {
        return $this->lockTime;
    }
    
    /**
     * setLockTime
     *
     * @param DateTime|null
     */
    public function setLockTime(\DateTime $lockTime = null)
    {
        $this->lockTime = $lockTime;
        return $this;
    }
    
}