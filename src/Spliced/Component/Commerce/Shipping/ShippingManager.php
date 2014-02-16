<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping;

use Spliced\Component\Commerce\Cart\CartManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Shipping\Model\ShippingProviderInterface;
use Spliced\Component\Commerce\Shipping\Model\ShippingMethodInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ShippingManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ShippingManager
{
    /** @var ArrayCollection */
    protected $providers;
    
    /** @var ArrayCollection */
    protected $methods;

    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param CartManager $cartManager
     */
    public function __construct(ConfigurationManager $configurationManager, CartManager $cartManager)
    {
        $this->methods = new ShippingMethodCollection();
        $this->providers = new ShippingProviderCollection();      
        $this->cartManager = $cartManager;
        $this->configurationManager = $configurationManager;  
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
     * addMethod
     * 
     * @param ShippingMethodInterface $method
     */
    public function addMethod(ShippingMethodInterface $method)
    {
        $this->methods->add($method);
        return $this;
    }
    
    /**
     * getMethods
    
     * @return ArrayCollection
     */
    public function getMethods()
    {
        return $this->methods;
    }
    
    /**
     * addProvider
     * 
     * @param ShippingProviderInterface $provider
     */
    public function addProvider(ShippingProviderInterface $provider)
    {
        $methods = new ArrayCollection();
        foreach($this->getMethods() as $method) {
            if($method->getProvider()->getName() == $provider->getName()){
                $provider->addMethod($method);
            }
        }

        $this->providers->set($provider->getName(), $provider);
        return $this;
    }

    /**
     * getProviders
     *
     * @return ArrayCollection
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * getProvider
     * @param  string                    $name
     * @return ShippingProviderInterface
     */
    public function getProvider($name)
    {
        $value = $this->providers->get($name);
        if (!$value) {
            throw new \Exception(sprintf('Shipping Provider %s Does Not Exist',$name));
        }

        return $value;
    }

    /**
     * getProvider
     * @param  string                    $name
     * @return ShippingProviderInterface
     */
    public function getMethodByFullName($fullName)
    {
        $searchName = strtolower($fullName);
        foreach($this->getProviders() as $provider) {
            foreach($provider->getMethods() as $method){
                $matchName = strtolower(sprintf('%s_%s', $provider->getName(), $method->getName()));
                if($searchName == $matchName){
                    return $method;
                }
            }
        }
        
        throw new \Exception(sprintf('Shipping Method %s Does Not Exist',$searchName));
    }
    
    /**
     * getAvailableMethodsForDesination
     *
     * @param string $country
     * @param string $zipcode
     */
    public function getAvailableMethodsForDesination($country, $zipcode = null)
    {
        $methods = new ShippingMethodCollection();
        foreach($this->getProviders() as $provider){
            foreach($provider->getMethods() as $method){
                $allowedCountries = $method->getAllowedCountries();
                $excludedCountries = $method->getExcludedCountries();
                $isAllowed   = in_array($country, $allowedCountries);
                $isExcluded  = in_array($country, $excludedCountries);
                
                if(!count($allowedCountries) && count($excludedCountries)){
                    if(!$isExcluded){
                        $methods->set($method->getPrice(), $method);
                    }
                } else if(count($allowedCountries) && !count($excludedCountries)){
                    if($isAllowed){
                        $methods->add($method);
                    }
                } else {
                    if($isAllowed && !$isExcluded){
                        $methods->add($method);
                    }
                }
            }
        }
        
        // sort methods by lowest price
        $sortedMethods = array();
        foreach($methods as $method){
            $price = $method->getPrice();
            while(isset($sortedMethods[$price])){
                $price += 0.01;
            }
            $sortedMethods[$price] = $method;
        }
        ksort($sortedMethods);
        return new ShippingMethodCollection($sortedMethods);        
    }

}
