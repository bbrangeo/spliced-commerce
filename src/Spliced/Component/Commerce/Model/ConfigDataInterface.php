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
     * setConfigLabel
     *
     * @param string $configLabel
     */
    public function setConfigLabel($configLabel);
    
    /**
     * getConfigLabel
    */
    public function getConfigLabel();
    
    /**
     * setConfigHelp
     *
     * @param string $configHelp
     */
    public function setConfigHelp($configHelp);
    
    /**
     * getConfigHelp
    */
    public function getConfigHelp();
    
    /**
     * setConfigGroup
     *
     * @param string $configGroup
     */
    public function setConfigGroup($configGroup);
    
    /**
     * getConfigGroup
    */
    public function getConfigGroup();
    

    /**
     * setConfigPosition
     *
     * @param string $configPosition
     */
    public function setConfigPosition($configPosition);
    
    /**
     * getConfigPosition
    */
    public function getConfigPosition();
    
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
     * setIsRequired
     *
     * @param bool $isRequired
     */
    public function setIsRequired($isRequired);
    
    /**
     * getIsRequired
    */
    public function getIsRequired();
}
