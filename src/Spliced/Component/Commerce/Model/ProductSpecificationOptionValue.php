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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ProductSpecificationOptionValue
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ProductSpecificationOptionValue
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\String
     */
    protected $value;

    /**
     * @MongoDB\String
     */
    protected $publicValue;
    
    /**
     * @MongoDB\Int
     */
    protected $position;


    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return text
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set publicValue
     *
     * @param text $publicValue
     */
    public function setPublicValue($publicValue)
    {
        $this->publicValue = $publicValue;

        return $this;
    }

    /**
     * Get publicValue
     *
     * @return text
     */
    public function getPublicValue()
    {
        return $this->publicValue ? $this->publicValue : $this->value;
    }

    /**
     * setPosition
     *
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * getPosition
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

}
