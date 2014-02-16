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

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Shipping\ShippingMethodCollection;
use Spliced\Component\Commerce\Shipping\ShippingManager;
/**
 * ShippingProvider
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
abstract class ShippingProvider implements ShippingProviderInterface
{
    /** Provider Constants */
    const PROVIDER_UPS         = 'ups';
    const PROVIDER_USPS     = 'usps';
    const PROVIDER_FEDEX     = 'fedex';
    const PROVIDER_DHL         = 'dhl';
    const PROVIDER_OTHER     = 'other';
    
    /**
     * ShippingMethodCollection
     */
    protected $methods;

    /**
     * @{inheritDoc} 
     */
    public function __construct(ConfigurationManager $configurationManager, CartManager $cartManager)
    {
        $this->configurationManager = $configurationManager;
        $this->cartManager = $cartManager;
        $this->methods = new ShippingMethodCollection();
    }
        
    /**
     * getConfigurationManager
     * 
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    
    /**
     * getCartManager
     *
     * @return CartManager
     */
    public function getCartManager()
    {
        return $this->cartManager;
    }
    
    /**
     * @{inheritDoc}
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @{inheritDoc}
     */
    public function getMethod($name)
    {
        $value = null;
        foreach($this->methods as $method){
            if($method->getName() == $name){
                return $method;
            }
        }
        
        throw new \Exception(sprintf('Shipping Method %s For Provider %s Does Not Exist. Available methods are %s',
            $name, 
            $this->getName(),
            implode(', ', $this->getAvailableMethodNames())
        ));
    }

    /**
     * @{inheritDoc}
     */
    public function hasMethod($name)
    {
        return $this->methods->has($name);
    }
    

    /**
     * @{inheritDoc}
     */
    public function addMethod(ShippingMethodInterface $method)
    {
         $this->methods->set($method->getName(), $method);
         return $this;
    }
    
    /**
     * 
     */
     public function getAvailableMethodNames()
     {
         $return = array();
        foreach($this->methods as $method){
            $return[] = $method->getName();
        }
        return $return;
     }
}
