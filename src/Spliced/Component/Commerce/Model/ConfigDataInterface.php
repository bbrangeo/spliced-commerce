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

/**
 * ConfigDataInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ConfigDataInterface
{
    
    
    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key);
    /**
     * Get key
     *
     * @return string
     */
    public function getKey();
    
    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value);
    
    /**
     * Get value
     *
     * @return text
     */
    public function getValue();
    
    /**
     * setType
     * 
     * @param string $type
     */
    public function setType($type);
    
    /**
     * getType
     */
    public function getType();
    
    /**
     * setLabel
     *
     * @param string $label
     */
    public function setLabel($label);
    
    /**
     * getLabel
     * 
     * @return string
    */
    public function getLabel();
    
    /**
     * setHelp
     *
     * @param string $help
     */
    public function setHelp($help);
    
    /**
     * getHelp
     * 
     * @return string
    */
    public function getHelp();
    
    /**
     * setGroup
     *
     * @param string $group
     */
    public function setGroup($group);
    
    /**
     * getGroup
    */
    public function getGroup();
    

    /**
     * setPosition
     *
     * @param string $configPosition
     */
    public function setPosition($position);
    
    /**
     * getPosition
    */
    public function getPosition();
    
    /**
     * setCreatedAt
     * 
     * @param DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt);
    
    /**
     * getCreatedAt
     */
    public function getCreatedAt();
    
    /**
     * setUpdatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt);
    
    /**
     * getUpdatedAt
     */
    public function getUpdatedAt();
    
    /**
     * setRequired
     *
     * @param bool $required
     */
    public function setRequired($required);
    
    /**
     * getRequired
    */
    public function getRequired();
}
