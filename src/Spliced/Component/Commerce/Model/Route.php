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
 * Route
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="route")
 */
abstract class Route implements RouteInterface
{
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Category")
     */
    protected $category;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="Product")
     */
    protected $product;
    
    /**
     * @MongoDB\ReferenceOne(targetDocument="CmsPage")
     */
    protected $page;
    
    /**
     * @MongoDB\String
     */
    protected $otherId;
    
    /**
     * @MongoDB\String
     * @MongoDB\Index(unique=true)
     */
    protected $requestPath;
    
    /**
     * @MongoDB\String
     */
    protected $targetPath;
    
    /**
     * @MongoDB\Hash
     */
    protected $options;
    
    /**
     * @MongoDB\String
     */
    protected $description;

    /**
     * 
     */
    public function __construct()
    {
        $this->options = array();
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
     * setCategory
     *
     * @param CategoryInterface $category
     */
    public function setCategory(CategoryInterface $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * getCategory
     *
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }

    
    /**
     * Set product
     *
     * @param $route
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get getProduct
     *
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }
    
    /**
     * setPage
     *
     * @param bigint $page
     */
    public function setPage(CmsPageInterface $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * getPage
     *
     * @return CmsPageInterface
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set otherId
     *
     * @param bigint $otherId
     */
    public function setOtherId($otherId)
    {
        $this->otherId = $otherId;

        return $this;
    }

    /**
     * Get otherId
     *
     * @return bigint
     */
    public function getOtherId()
    {
        return $this->otherId;
    }

    /**
     * Set requestPath
     *
     * @param string $requestPath
     */
    public function setRequestPath($requestPath)
    {
        $this->requestPath = preg_match('/^\//',$requestPath) ? $requestPath : '/'.$requestPath;

        return $this;
    }

    /**
     * Get requestPath
     *
     * @return string
     */
    public function getRequestPath()
    {
        return $this->requestPath;
    }

    /**
     * Set targetPath
     *
     * @param string $targetPath
     */
    public function setTargetPath($targetPath)
    {
        $this->targetPath = $targetPath;

        return $this;
    }

    /**
     * Get targetPath
     *
     * @return string
     */
    public function getTargetPath()
    {
        return $this->targetPath;
    }

    /**
     * Set options
     *
     * @param string $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Add option
     *
     * @param string $option
     * @param string $value
     */
    public function addOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
    }
    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}
