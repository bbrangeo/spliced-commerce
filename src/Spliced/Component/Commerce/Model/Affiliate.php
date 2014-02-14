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
 * Affiliate
 * 
 * @MongoDB\Document(collection="affiliate")
 */
abstract class Affiliate implements AffiliateInterface
{ 
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex
     */
    protected $name;
    
    /**
     * @MongoDB\String
     */
    protected $website;

    /**
     * @MongoDB\String
     */
    protected $orderPrefix;
    
    /**
     * @MongoDB\Collection
     */
    protected $referrerUrls;
    
    /**
     * @MongoDB\Boolean
     */
    protected $isComissioned;
    
    /**
     * @MongoDB\Boolean
     */
    protected $isActive;
    
    /**
     * getId
     *
     * @param string $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * setName
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setWebsite
     *
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * getWebsite
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * setIsComissioned
     *
     * @param boolean $isComissioned
     */
    public function setIsComissioned($isComissioned)
    {
        $this->isComissioned = $isComissioned;
        return $this;
    }

    /**
     * getIsComissioned
     *
     * @return boolean
     */
    public function getIsComissioned()
    {
        return $this->isComissioned;
    }

    /**
     * setIsActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * getIsActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * setOrderPrefix
     *
     * @param string $urlPrefix
     */
    public function setOrderPrefix($orderPrefix)
    {
        $this->orderPrefix = $orderPrefix;
    }
    
    /**
     * getOrderPrefix
     *
     * @return string
     */
    public function getOrderPrefix()
    {
        return $this->orderPrefix;
    }
    
    /**
     * getReferrerUrls
     * 
     * @return string
     */
    public function getReferrerUrls()
    {
        return $this->referrerUrls;

    }
    
    /**
     * setReferrerUrls
     * 
     * @param array $referrerUrls
     */
    public function setReferrerUrls(array $referrerUrls)
    {
        $this->referrerUrls = $referrerUrls;
        return $this;
    }
    
}