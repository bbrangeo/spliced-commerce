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
 * ProductManufacturer
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="manufacturer")
 */
abstract class Manufacturer implements ManufacturerInterface
{
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\String()
     * @MongoDB\UniqueIndex()
     */
    protected $name;

    /**
     * @MongoDB\Date
     */
    protected $createdAt;

    /**
     * @MongoDB\Date
     */
    protected $updatedAt;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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
     * getName
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * getCreatedAt
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * setCreatedAt
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * getUpdatedAt
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * setUpdatedAt
     *
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}