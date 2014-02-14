<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ODM\MongoDB\Cursor;

/**
 * ConfigurationManager
 * 
 * Handles configuration parameter access as well creates entities
 * for the respected object managers
 * 
 * @author Gasasn Idriss <ghassani@splicedmedia.com>
 */
class ConfigurationManager
{
	/** These are the default commerce implementations entity class tags for 
	 * use with creating entities with this service. 
	 */
    const OBJECT_CLASS_TAG_AFFILIATE 			            = 'affiliate';
    const OBJECT_CLASS_TAG_CUSTOMER 			            = 'customer';
    const OBJECT_CLASS_TAG_CUSTOMER_ADDRESS 	            = 'customer_address';
    const OBJECT_CLASS_TAG_CUSTOMER_PROFILE 	            = 'customer_profile';
    const OBJECT_CLASS_TAG_CHECKOUT_CUSTOM_FIELD            = 'checkout_custom_field';
    const OBJECT_CLASS_TAG_CART                             = 'cart';
    const OBJECT_CLASS_TAG_CART_ITEM                        = 'cart_item';
    const OBJECT_CLASS_TAG_CATEGORY                         = 'category';
    const OBJECT_CLASS_TAG_PRODUCT 				            = 'product';
    const OBJECT_CLASS_TAG_PRODUCT_CONTENT 		            = 'product_content';
    const OBJECT_CLASS_TAG_PRODUCT_CATEGORY 	            = 'product_category';
    const OBJECT_CLASS_TAG_PRODUCT_IMAGE 		            = 'product_image';
    const OBJECT_CLASS_TAG_PRODUCT_TIER_PRICE 		        = 'product_tier_price';
    const OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE 				= 'product_attribute';
    const OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION 	 	= 'product_attribute_option';
    const OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION_VALUE 	= 'product_attribute_option_value';
    const OBJECT_CLASS_TAG_PRODUCT_ATTRIBUTE_OPTION_VALUE_PRODUCT = 'product_attribute_option_value_product';
    const OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION 			= 'product_specification';
    const OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION 	 	= 'product_specification_option';
    const OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION_VALUE 	= 'product_specification_option_value';
    const OBJECT_CLASS_TAG_PRODUCT_BUNDLED_ITEM             = 'product_bundled_item';
    const OBJECT_CLASS_TAG_PRODUCT_UPSALE                   = 'product_upsale';
    const OBJECT_CLASS_TAG_MANUFACTURER                     = 'manufacturer';
    const OBJECT_CLASS_TAG_ORDER 				            = 'order';
    const OBJECT_CLASS_TAG_ORDER_ITEM			            = 'order_item';
    const OBJECT_CLASS_TAG_ORDER_MEMO			            = 'order_memo';
    const OBJECT_CLASS_TAG_ORDER_PAYMENT 		            = 'order_payment';
    const OBJECT_CLASS_TAG_ORDER_PAYMENT_MEMO 	            = 'order_payment_memo';
    const OBJECT_CLASS_TAG_ORDER_SHIPMENT 		            = 'order_shipment';
    const OBJECT_CLASS_TAG_ORDER_SHIPMENT_MEMO 	            = 'order_shipment_memo';
    const OBJECT_CLASS_TAG_ORDER_CUSTOM_FIELD_VALUE         = 'order_custom_field_value';
    const OBJECT_CLASS_TAG_CONFIG_DATA 		                = 'config_data';
    const OBJECT_CLASS_TAG_VISITOR 		                    = 'visitor';
    const OBJECT_CLASS_TAG_VISITOR_REQUEST 		            = 'visitor_request';
    const OBJECT_CLASS_TAG_SEARCH_TERM 		                = 'search_term';
    const OBJECT_CLASS_TAG_NEWSLETTER_SUBSCRIBER 		    = 'newsletter_subscriber';
    const OBJECT_CLASS_TAG_CONTACT_MESSAGE 		            = 'contact_message';
    const OBJECT_CLASS_TAG_USER		           				= 'user';
    const OBJECT_CLASS_TAG_ROUTE                            = 'route';
    const OBJECT_CLASS_TAG_TAG                              = 'tag';
    const OBJECT_CLASS_TAG_CMS_PAGE                         = 'cms_page';
    const OBJECT_CLASS_TAG_CMS_BLOCK                        = 'cms_block';
    
    
    protected $om;
    protected $router;
    protected $data = false;
	protected $configurableServices;
	
    /**
     * Constructor
     *
     * @param array $entityClasses
     * @param ObjectManager $om
     * @param AppKernel $kernel
     */
    public function __construct(array $entityClasses, array $documentClasses, EntityManager $em, DocumentManager $dm, \AppKernel $kernel)
    {
    	$this->em = $em;
    	$this->dm = $dm;
        $this->entityClasses = $entityClasses;
        $this->documentClasses = $documentClasses;
        $this->kernel = $kernel;
        $this->configurableServices = new ArrayCollection();
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
    	return $this->em;
    }
    

    /**
     * getDocumentManager
     *
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
    	return $this->dm;
    }
    
    /**
     * getKernel
     *
     * @return Kernel
     */
    public function getKernel()
    {
    	return $this->kernel;
    }
    
    /**
     * getWebDir
     * 
     * Returns the full path to the web directory
     * 
     * @return string
     */
    public function getWebDir()
    {
        return $this->getKernel()->getRootDir().'/../web';    
    }
    
    /**
     * init
     */
    public function init()
    {
    	$data = $this->getDocumentManager()
    	  ->getRepository($this->getDocumentClass(static::OBJECT_CLASS_TAG_CONFIG_DATA))
    	  ->getConfiguration($this->getKernel()->getEnvironment() == 'prod');
    	
    	if($data instanceof Cursor){
    		$data = $data->toArray();
    	}

    	$this->data = array();
    	foreach ($data as $d) {
    		if(isset($d['value']) && in_array($d['type'],array('array','serialized','countries','statuses')) && !is_array($d['value'])){
    			$this->data[$d['key']] = unserialize($d['value']);
    		} else {
    			$this->data[$d['key']] =  isset($d['value']) ? $d['value'] : null;
    		}
    	}

    }
    
    /**
     * get
     *
     * @param string $key
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
    	if(!is_array($this->data)){
    		$this->init();
    	}
    	
        return isset($this->data[$key]) ? $this->data[$key] : $defaultValue;
    }

    /**
     * has
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
    	if(!is_array($this->data)){
    		$this->init();
    	}
    	
    	return array_key_exists($key, $this->data);
    }

    /**
     * add
     *
     * @param string $key
     * @param string $value
     * @param string $type
     *
     * @return ConfigurationManager
     */
    public function add($key, array $fieldParams)
    {
        if(!$this->data){
    		$this->init();
    	}
    	
    	if ($this->has($key)) {
            return $this;
        }

        $config = $this->getDocumentManager()
          ->getRepository($this->getDocumentClass(static::OBJECT_CLASS_TAG_CONFIG_DATA))
          ->findOneByKey($key);

        if (!$config) {
            $config = $this->createDocumentClass(static::OBJECT_CLASS_TAG_CONFIG_DATA);
            $config->setKey($key);
            $config->setType($fieldParams['type']); //type before value
            $config->setValue(is_array($fieldParams['value']) ? serialize($fieldParams['value']) : $fieldParams['value']);
            $config->setConfigGroup($fieldParams['group']);
            $config->setConfigLabel($fieldParams['label']);
            $config->setConfigHelp($fieldParams['help']);
            $config->setConfigPosition($fieldParams['position']);
            $config->setIsRequired($fieldParams['required']);

            $this->getDocumentManager()->persist($config);
            $this->getDocumentManager()->flush();

            $this->data[$key] = $config->getValue();
        }
        
        return $this;
    }

    /**
     * getByKeyExpression
     *
     * @param $expression - A valid Regular Expression
     * @return array
     */
    public function getByKeyExpression($expression)
    {
        if(!$this->data){
    		$this->init();
    	}
    	
    	$return = array();
        array_walk($this->data, function($value,$key) use ($expression, &$return) {
            if (preg_match($expression,$key)) {
                $return[$key] = $value;
            }

            return true;
        });

        return $return;
    }

    /**
     * processConfiguration
     * 
     * TODO: remove
     * 
     * @param ConfigurableInterface $configurationInterface
     */
    public function processConfiguration(ConfigurableInterface $configurationInterface){
    	return $this->processConfigurableService($configurationInterface);
    }
    
    /**
     * processConfigurableService
     * 
     * @param ConfigurableInterface $configurationInterface
     */
    public function processConfigurableService(ConfigurableInterface $configurationInterface)
    {
        foreach ($configurationInterface->getRequiredConfigurationFields() as $fieldName => $fieldParams) {
            $configKey = sprintf('%s.%s',$configurationInterface->getConfigPrefix(), $fieldName);
            if (!$this->has($configKey)) {
                $this->add($configKey, $fieldParams);
            }
        }
    }

    /**
     * processConfigurableServices
     * 
     * @param ContainerInterface $container
     */
    public function processConfigurableServices(ContainerInterface $container)
    {
    	foreach($this->getConfigurableServiceIds() as $configurableServiceId){
    		if($container->has($configurableServiceId)){
    			$this->processConfiguration($container->get($configurableServiceId));
    		}
    	}
    }
    
    /**
     * addConfigurableServiceId
     *
     * @param string $configurableInterfaceServiceId
     */
    public function addConfigurableServiceId($configurableInterfaceServiceId)
    {
    	$this->configurableServices->add($configurableInterfaceServiceId);
    }
    
    /**
     * getConfigurableServiceIds
     *
     * @return ArrayCollection
     */
    public function getConfigurableServiceIds()
    {
    	return $this->configurableServices;
    }
    
    /**
     * getEntityClasses
     * 
     * @return array
     */
    protected function getEntityClasses()
    {
        return $this->entityClasses;
    }

    /**
     * getEntityClass
     * 
     * @param string $entityTag
     * 
     * @return string
     */
    public function getEntityClass($entityTag)
    {
        return isset($this->entityClasses[$entityTag]) ? $this->entityClasses[$entityTag] : null;
    }

    /**
     * createEntity
     * 
     * @param string $entityTag
     * 
     * @return mixed
     */
    public function createEntity($entityTag)
    {
        $class = $this->getEntityClass($entityTag);
        if (!class_exists($class)) {
            throw new \Exception(sprintf('Class %s Does Not Exist From Tag %s',$class, $entityTag));
        }

        return new $class();
    }
    
    /**
     * getDocumentClasses
     *
     * @return array
     */
    protected function getDocumentClasses()
    {
    	return $this->documentClasses;
    }
    
    /**
     * getDocumentClass
     *
     * @param string $documentTag
     *
     * @return string
     */
    public function getDocumentClass($documentTag)
    {
    	return isset($this->documentClasses[$documentTag]) ? $this->documentClasses[$documentTag] : null;
    }
    
    /**
     * createDocument
     *
     * @param string $documentTag
     *
     * @return mixed
     */
    public function createDocument($documentTag)
    {
    	$class = $this->getDocumentClass($documentTag);
    	if (!class_exists($class)) {
    		throw new \Exception(sprintf('Class %s Does Not Exist From Tag %s',$class, $documentTag));
    	}
    
    	return new $class();
    }
}
