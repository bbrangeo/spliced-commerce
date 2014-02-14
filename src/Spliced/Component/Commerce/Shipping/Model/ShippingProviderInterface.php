<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping\Model;

/**
 * ShippingProviderInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ShippingProviderInterface
{

	/**
	 * getName
	 * 
	 * @return string
	 */
	public function getName();
	
    /**
     * getMethods
     * 
     * @return ShippingMethodCollection
     */
    public function getMethods();
    
    /**
     * hasMethod
     * 
     * @param string $name
     * 
     * @return boolean
     */
    public function hasMethod($name);
    
    /**
     * getMethod
     * 
     * @param string $name
     * 
     * @return ShippingMethodInterface
     */
    public function getMethod($name);
    
    /**
     * addMethod
     * 
     * @param ShippingMethodInterface $method
     */
    public function addMethod(ShippingMethodInterface $method);
}
