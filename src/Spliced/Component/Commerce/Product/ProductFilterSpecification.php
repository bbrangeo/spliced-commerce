<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Product;

use Spliced\Component\Commerce\Model\ProductSpecificationOptionInterface;

/**
 * ProductFilterSpecification
 * 
 * Data structure representing an applicable specification to
 * use as a filter to show available options to filter the product
 * collection by
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductFilterSpecification
{
    /** @var ProductSpecificationOptionInterface */
    protected $option;
    
    /** @var array */
    protected $values = array();
    
    /**
     * Constructor
     * 
     * @param ProductSpecificationOptionInterface $option
     */
    public function __construct(ProductSpecificationOptionInterface $option)
    {
        $this->option = $option;
    }
    
    /**
     * getOption
     * 
     * @return ProductSpecificationOptionInterface
     */
    public function getOption()
    {
        return $this->option;
    }
    
    /**
     * addValue
     * 
     * @param string $value
     */
    public function addValue($value)
    {
        $this->values[] = $value;
        $this->values = array_unique($this->values);
        return $this;
    }

    /**
     * addValues
     *
     * @param array $values
     */
    public function addValues(array $values)
    {
        $this->values = array_unique(array_merge($this->values, $values));
        return $this;
    }
    
    /**
     * setValues
     *
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->values = $values;
        return $this;
    }
    
    /**
     * getValues
     * 
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}