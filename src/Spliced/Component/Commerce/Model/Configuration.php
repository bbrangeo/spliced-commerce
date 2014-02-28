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
use Spliced\Component\Commerce\Doctrine\ODM\MongoDB\Mapping\Annotations as Commerce;

/**
 * Configuration
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="configuration")
 */
abstract class Configuration implements ConfigurationInterface
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
     * @Commerce\ConfigurationValue
     */
    protected $value;
    
    /**
     * @MongoDB\String
     * @MongoDB\Index
     */
    protected $group;
    
    /**
     * @MongoDB\String
     * @MongoDB\Index
     */
    protected $childGroup;
    
    /**
     * @MongoDB\Int
     */
    protected $position;
    
    /**
     * @MongoDB\String
     */
    protected $label;
    
    /**
     * @MongoDB\String
     */
    protected $help;
    
    /**
     * @MongoDB\Boolean
     */
    protected $required;

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
     * getFormSafeKey
     * 
     * @return string
     */
    public function getFormSafeKey()
    {
        return str_replace('.', '_', $this->key);
    }
    
    /**
     * {@inheritDoc}
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
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
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getGroup()
    {
        return $this->group;
    }
    

    /**
     * {@inheritDoc}
     */
    public function setChildGroup($childGroup)
    {
        $this->childGroup = $childGroup;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getChildGroup()
    {
        return $this->childGroup;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPosition()
    {
        return $this->position;
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
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setRequired($isRequired)
    {
        $this->required = $isRequired;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getRequired()
    {
        return $this->required;
    }
    
    /**
     * isRequired
     * 
     * Proxy method for getRequired
     * 
     * @return bool
     */
    public function isRequired()
    {
        return $this->getRequired();
    }


    /**
     * {@inheritDoc}
     */
    public function setHelp($help)
    {
        $this->help = $help;
        return $this;
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function getHelp()
    {
        return $this->help;
    }
    
}