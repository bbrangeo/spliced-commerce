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
 * ProductAttributeOption
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * 
 * @MongoDB\Document(collection="product_attribute_option")
 */
abstract class ProductAttributeOption implements ProductAttributeOptionInterface
{

    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\String
     * @MongoDB\Index(unique=true)
     */
    protected $key;
    
    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\String
     */
    protected $publicLabel;

    /**
     * @MongoDB\String
     */
    protected $description;

    /**
     * @MongoDB\Int
     * @MongoDB\Index()
     */
    protected $position;

    /**
     * @MongoDB\Int
     * @MongoDB\Index()
     */
    protected $optionType;

    /**
     * @MongoDB\Boolean
     * @MongoDB\Index()
     */
    protected $filterable;

    /**
     * @MongoDB\Boolean
     * @MongoDB\Index()
     */
    protected $onView;
	
    /**
     * @MongoDB\Boolean
     * @MongoDB\Index()
     */
    protected $onList;

    /**
     * @MongoDB\Date
     */
    protected $createdAt;
    
    /**
     * @MongoDB\Date
     */
    protected $updatedAt;

    /**
     * @MongoDB\Hash
     */
    protected $optionData;
    
    /**
     * @MongoDB\EmbedMany(targetDocument="ProductAttributeOptionValue")
     */
    protected $values;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = new ArrayCollection();
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
        $this->setOptionType(ProductAttributeOptionInterface::OPTION_TYPE_USER_DATA_INPUT);
		$this->onView = false;
		$this->onList = false;
		$this->filterable = false;
		$this->optionData = array();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    
        return $this;
    }
    
    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set publicLabel
     *
     * @param string $publicLabel
     */
    public function setPublicLabel($publicLabel)
    {
        $this->publicLabel = $publicLabel;

        return $this;
    }

    /**
     * Get publicLabel
     *
     * @return string
     */
    public function getPublicLabel()
    {
        return $this->publicLabel;
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

    /**
     * Set onFront
     *
     * @param boolean $onFront
     */
    public function setFilterable($filterable)
    {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * Get filterable
     *
     * @return boolean
     */
    public function getFilterable()
    {
        return $this->filterable;
    }

    /**
     * setOnList
     *
     * @param boolean $onFront
     */
    public function setOnList($onList)
    {
        $this->onList = $onList;

        return $this;
    }

    /**
     * getOnList
     *
     * @return boolean
     */
    public function getOnList()
    {
        return $this->onList;
    }
	
    /**
     * setOnView
     *
     * @param boolean $onView
     */
    public function setOnView($onView)
    {
        $this->onView = $onView;

        return $this;
    }

    /**
     * getOnView
     *
     * @return boolean
     */
    public function getOnView()
    {
        return $this->onView;
    }
    /**
     * Set optionType
     *
     * @param int $optionType
     */
    public function setOptionType($optionType)
    {
        $this->optionType = $optionType;

        return $this;
    }

    /**
     * Get optionType
     *
     * @return int
     */
    public function getOptionType()
    {
        return $this->optionType;
    }
    
    /**
     * getOptionTypeName
     *
     * @return string
     */
    public function getOptionTypeName()
    {
        switch($this->getOptionType()){
            case 1:
                return 'Single Value';
                break;
            case 2:
                return 'Multi Value';
                break;
            case 3:
                return 'User Selection (Price Altering)';
                break;
            case 4:
                return 'User Input';
                break;
            default:
                return 'Undefined';
                break;
        }
    }
    
    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
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
    public function setUpdatedAt(\DateTime $updatedAt)
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
     * addValue
     *
     * @param ProductAttributeOptionValue $value
     */
    public function addValue(ProductAttributeOptionValue $value)
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
        }

        return $this;
    }
    
    /**
     * removeValue
     *
     * @param ProductAttributeOptionValue $value
     */
    public function removeValue(ProductAttributeOptionValue $value)
    {
        $this->values->removeElement($value);
    }
    
    /**
     * getValues
     *
     * @return Collection
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * setValues
     *
     * @param Collection $values
     */
    public function setValues(Collection $values)
    {
        $this->values = $values;
        return $this;
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
	
	/**
	 * getOptionData
	 * 
	 * @return array
	 */
	 public function getOptionData()
	 {
	 	return $this->optionData;
	 }
	 
	
	/**
	 * setOptionData
	 * 
	 * @param array $optionData
	 */
	 public function setOptionData(array $optionData = array())
	 {
	 	$this->optionData = $optionData;
		return $this;
	 }
	 
}
