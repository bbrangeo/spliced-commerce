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
 * ConfigData
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="config_data")
 */
abstract class ConfigData implements ConfigDataInterface
{

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $type;

    /**
     * @MongoDB\String
     * @MongoDB\Index(unique=true)
     */
    protected $key;

    /**
     * @MongoDB\String
     */
    protected $value;
    
    /**
     * @MongoDB\String
     */
    protected $configGroup;
    
    /**
     * @MongoDB\Int
     */
    protected $configPosition;
    
    /**
     * @MongoDB\String
     */
    protected $configLabel;
    
    /**
     * @MongoDB\String
     */
    protected $configHelp;
    
    /**
     * @MongoDB\Boolean
     */
    protected $isRequired;

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
        return $this->getValue();
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
     * {@inheritDoc}
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setValue($value)
    {
    
        switch ($this->getType()) {
            default:
                break;
            case 'boolean':
                $value = $value ? true : false;
                break;
            case 'integer':
                $value = (int) $value;
                break;
            case 'float':
                $value = (float) $value;
                break;
            case 'serialized':
            case 'array':
                $value = serialize($value);
                break;
        }
    
        $this->value = $value;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        if(in_array($this->getType(),array('array','countries'))){
            return unserialize($this->value);
        }
        return $this->value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }  
    

    /**
     * {@inheritDoc}
     */
    public function setConfigGroup($configGroup)
    {
        $this->configGroup = $configGroup;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigGroup()
    {
        return $this->configGroup;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setConfigPosition($configPosition)
    {
        $this->configPosition = $configPosition;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfigPosition()
    {
        return $this->configPosition;
    }
    

    /**
     * {@inheritDoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    

    /**
     * {@inheritDoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setConfigLabel($configLabel)
    {
        $this->configLabel = $configLabel;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getConfigLabel()
    {
        return $this->configLabel;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }


    /**
     * {@inheritDoc}
     */
    public function setConfigHelp($configHelp)
    {
        $this->configHelp = $configHelp;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getConfigHelp()
    {
        return $this->configHelp;
    }
    
}