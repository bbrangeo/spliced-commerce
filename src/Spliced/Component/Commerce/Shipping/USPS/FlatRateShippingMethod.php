<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Shipping\USPS;

use Spliced\Component\Commerce\Shipping\Model\ConfigurableShippingMethod;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Spliced\Component\Commerce\Shipping\Model\ShippingProvider;
use Spliced\Component\Commerce\Shipping\Model\ShippingProviderInterface;
use Spliced\Component\Commerce\Shipping\Model\ShippingMethodInterface;
use Spliced\Component\Commerce\Cart\CartManager;

/**
 * FlatRateShippingMethod
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FlatRateShippingMethod extends ConfigurableShippingMethod
{
    /**
     * Constructor
     * 
     * @param string $name
     * @param ShippingProviderInterface $provider
     * @param array $defaultConfigurationValues
     */
    public function __construct($name, ShippingProviderInterface $provider, array $defaultConfigurationValues = array())
    {
        if(empty($name)){
            throw new \InvalidArgumentException('Fedex\FlatRateShippingMethod requires a value for argument $name');
        }

        $this->defaultConfigurationValues = $defaultConfigurationValues;
        
        parent::__construct($name, $provider);
    } 
    
    /**
     * {@inheritDoc}
     */
    public function getConfigurationManager()
    {
        return $this->getProvider()->getConfigurationManager();
    }
    
    /**
     * getCartManager
     * 
     * @return CartManager
     */
    public function getCartManager()
    {
        return $this->getProvider()->getCartManager();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {

           return array(
                   
               'label' => array(
                   'type' => 'string',
                   'value' => isset($this->defaultConfigurationValues['label']) ? $this->defaultConfigurationValues['label'] : null,
                   'label' => 'Label',
                   'help' => '',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 0,
                   'required' => false,
               ),

               'label2' => array(
                   'type' => 'string',
                   'value' => isset($this->defaultConfigurationValues['label2']) ? $this->defaultConfigurationValues['label2'] : null,
                   'label' => 'Label 2',
                   'help' => '',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 1,
                   'required' => false,
               ),

               'base_price' => array(
                   'type' => 'float',
                   'value' => isset($this->defaultConfigurationValues['base_price']) ? $this->defaultConfigurationValues['base_price'] : 0.00,
                   'label' => 'Base Price',
                   'help' => '',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 2,
                   'required' => false,
               ),
                   

               'cost' => array(
                   'type' => 'float',
                   'value' => isset($this->defaultConfigurationValues['cost']) ? $this->defaultConfigurationValues['cost'] : 0.00,
                   'label' => 'Cost',
                   'help' => '',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 3,
                   'required' => false,
               ),

               'allowed_countries' => array(
                   'type' => 'countries',
                   'value' => isset($this->defaultConfigurationValues['allowed_countries']) ? $this->defaultConfigurationValues['allowed_countries'] : array(),
                   'label' => 'Allowed Countries',
                   'help' => '',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 4,
                   'required' => false,
               ),
                   
               'excluded_countries' => array(
                   'type' => 'countries',
                   'value' => isset($this->defaultConfigurationValues['excluded_countries']) ? $this->defaultConfigurationValues['excluded_countries'] : array(),
                   'label' => 'Excluded Countries',
                   'help' => '',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 5,
                   'required' => false,
               ),
                   
               'tracking_url' => array(
                   'type' => 'string',
                   'value' => isset($this->defaultConfigurationValues['tracking_url']) ? $this->defaultConfigurationValues['tracking_url'] : null,
                   'label' => 'Tracking URL',
                   'help' => 'The URL To Track Shipments',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 6,
                   'required' => false,
               ),
                   
                   
            'over_x_items' => array(
                   'type' => 'integer',
                   'value' => isset($this->defaultConfigurationValues['over_x_items']) ? $this->defaultConfigurationValues['over_x_items'] : null,
                   'label' => 'When over x items',
                   'help' => 'When an order has over the specified count, add an additional cost per item',
                   'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                   'position' => 7,
                   'required' => false,
               ),

               'over_x_items_add' => array(
                   'type' => 'float',
                   'value' => isset($this->defaultConfigurationValues['over_x_items_add']) ? $this->defaultConfigurationValues['over_x_items_add'] : null,
                   'label' => 'When over x items Add',
                   'help' => 'When an order has over the specified count, this additional cost per item',
                'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                'position' => 8,
                'required' => false,
            ),    

            'over_x_items_match_sku' => array(
                'type' => 'string',
                'value' => isset($this->defaultConfigurationValues['over_x_items_match_sku']) ? $this->defaultConfigurationValues['over_x_items_match_sku'] : null,
                'label' => 'When over x items matching expression',
                'help' => 'When an order has over the specified count, and the set expression matches sku then add an additional cost per item',
                'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                'position' => 9,
                'required' => false,
            ),    
            
            'over_x_items_match_sku_add' => array(
                'type' => 'float',
                'value' => isset($this->defaultConfigurationValues['over_x_items_match_sku_add']) ? $this->defaultConfigurationValues['over_x_items_match_sku_add'] : null,
                'label' => 'When Over x Amount and Matched Expression',
                'help' => 'When an order has over the specified count, and the set expression matches sku then add this additional cost per item',
                'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                'position' => 9,
                'required' => false,
            ),    
               'enabled' => array(
                'type' => 'boolean',
                'value' => isset($this->defaultConfigurationValues['enabled']) ? $this->defaultConfigurationValues['enabled'] : true,
                'label' => 'Enabled',
                'help' => '',
                'group' => sprintf('Shipping/%s/%s', $this->getProvider()->getName(), $this->getName()),
                'position' => 11,
                'required' => false,
            ),
        );
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPrice()
    {
        $returnPrice = $this->getOption('base_price');
        $totalModules = 0;
        $realItems = array();
        $toAdd = 0.00;
    
        foreach ($this->getCartManager()->getItems() as $item){
            $totalQuantity = $item->getQuantity();
            for($i = 1; $i <= $totalQuantity; $i++){
                $items[] = $item->getProduct()->getSku();
            }
        }
        $totalModules = count($items);
         
        if($this->getOption('over_x_items') && $this->getOption('over_x_items_add')){
            $basePriceGoodUntil = (int) $this->getOption('over_x_items');
            $addEveryModulesAmount = (float) $this->getOption('over_x_items_add');
            $additionalCheckRegexp = $this->getOption('over_x_items_match_sku');
            $addEveryModulesAmountRegexp = (float) $this->getOption('over_x_items_match_sku_add');
            
            if($basePriceGoodUntil < $totalModules){
                if( $additionalCheckRegexp && $addEveryModulesAmountRegexp){
                    $regexp = sprintf('/%s/',$additionalCheckRegexp);
                    $i=1;
                    foreach ($items as $item){
                        if($i <= $basePriceGoodUntil){
                            $i++; // lets skip the first to the last allowed in base cost
                            continue;
                        }
    
                        if(preg_match($regexp,$item) !== false){
                            $toAdd += $addEveryModulesAmountRegexp;
                        } else {
                            $toAdd += $addEveryModulesAmount;
                        }
                    }
                        
                } else {
                    $remainder = $totalModules - $basePriceGoodUntil;
                    $toAdd += $addEveryModulesAmount * $remainder;
                }
            }
                
            $returnPrice += $toAdd;
        }
        return $returnPrice;
    }
    

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return $this->getOption('label',null);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLabel2()
    {
        return $this->getOption('label2',null);
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAllowedCountries()
    {
        return $this->getOption('allowed_countries',array());
    }
    
    /**
     * {@inheritDoc}
     */
    public function getExcludedCountries()
    {
        return $this->getOption('excluded_countries',array());
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCost()
    {
        return number_format($this->getOption('cost',0.0),2);
    }

}
