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

use Symfony\Component\HttpFoundation\Session\Session;
use Spliced\Component\Commerce\Model\CategoryInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * ProductFilterManager
 * 
 * Handles the filtering of products when viewing a collection of products
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductFilterManager
{
    /* Available List Mods by Default */
    const DISPLAY_MODE_GRID = 'grid';
    const DISPLAY_MODE_LIST = 'list';

    /** Session Tag Constants */
    const SESSION_TAG_PER_PAGE = 'commerce.product.filter.per_page';
    const SESSION_TAG_ORDER_BY = 'commerce.product.filter.order_by';
    const SESSION_TAG_DISPLAY_MODE = 'commerce.product.filter.display_mode';
    const SESSION_TAG_ATTRIBUTES = 'commerce.product.filter.attributes';

    /** CategoryInterface|null */
    protected $category = null;
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param Session $session
     */
    public function __construct(ConfigurationManager $configurationManager, Session $session)
    {
        $this->configurationManager = $configurationManager;
        $this->session = $session;
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
     * getSession
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * setPerPage
     *
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        if ($perPage > $this->configurationManager->get('commerce.category.filter.max_per_page')) {
            $perPage = $this->configurationManager->get('commerce.category.filter.max_per_page');
        }
        $this->session->set(static::SESSION_TAG_PER_PAGE, (int) $perPage);

        return $this;
    }

    /**
     * getPerPage
     *
     * @param  int|null $defaultReturn
     * @return int|null
     */
    public function getPerPage($defaultReturn = null)
    {
        if (is_null($defaultReturn)) {
            $defaultReturn = $this->configurationManager->get('commerce.category.filter.default_per_page');
        }

        return $this->session->get(static::SESSION_TAG_PER_PAGE, $defaultReturn);
    }

    /**
     * setOrderBy
     *
     * @param string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->session->set(static::SESSION_TAG_ORDER_BY, $orderBy);

        return $this;
    }

    /**
     * getOrderBy
     *
     * @param string|null $defaultReturn
     */
    public function getOrderBy($defaultReturn = null)
    {
        if (is_null($defaultReturn)) {
            $defaultReturn = $this->configurationManager->get('commerce.category.filter.default_order_by');
        }

        return $this->session->get(static::SESSION_TAG_ORDER_BY, $defaultReturn);
    }

    /**
     * setDisplayMode
     *
     * @param string $displayMode
     */
    public function setDisplayMode($displayMode)
    {
        $this->session->set(static::SESSION_TAG_DISPLAY_MODE, $displayMode);

        return $this;
    }

    /**
     * getDisplayMode
     *
     * @param string|null $defaultReturn
     */
    public function getDisplayMode($defaultReturn = null)
    {
        if (is_null($defaultReturn)) {
            $defaultReturn = $this->configurationManager->get('commerce.category.filter.default_display_mode');
        }

        return $this->session->get(static::SESSION_TAG_DISPLAY_MODE, $defaultReturn);
    }


     /**
      * getOrderByLabel
      * 
      * @return string
      */
     public function getOrderByLabel()
     {
         switch (strtolower($this->getOrderBy())) {
            default:
            case 'price_asc':
                return 'Price Ascending';
                break;
            case 'price_desc':
                return 'Price Descending';
                break;
            case 'name_asc':
                return 'Name Ascending';
                break;
            case 'name_desc':
                return 'Name Descending';
                break;
            case 'sku_asc':
                return 'Sku Ascending';
                break;
            case 'sku_desc':
                return 'Sku Descending';
                break;
         }
     }

     
     /**
      * setCategory
      * 
      * @param CategoryInterface|null
      */
     public function setCategory(CategoryInterface $category = null)
     {
         $this->category = $category;
         return $this;
     }
     
     /**
      * getCategory
      * 
      * @return CategoryInterface|null
      */
     public function getCategory()
     {
          return $this->category;
     }
     
     /**
      * getPaginationQuery
      * 
      * @return QueryBuilder
      */
     public function getPaginationQuery()
     {
         $productRepository = $this->configurationManager->getDocumentManager()
           ->getRepository($this->configurationManager->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT));
         
         if( $this->getCategory()) {
             $query = $productRepository->findByCategoryQuery($this->getCategory());
         } else {
             $query = $productRepository->createQueryBuilder('product');
         }
         
         return $query;
     }
     
     /**
      * getAvailableSpecifications
      * 
      * 
      * @return array
      */
     public function getAvailableSpecifications()
     {
         // lets load, as an array, just all product specifications
         // matching the current criteria
         $productSpecifications = $this->getPaginationQuery()
           ->select('specifications')
           ->hydrate(false)
           ->getQuery() 
           ->execute();

         // extract the values and option keys into an array
         $specificationKeys = array();
         foreach($productSpecifications as $productSpecification){
         	 if(!isset($productSpecification['specifications'])){
         	 	continue;
         	 }
             foreach($productSpecification['specifications'] as $specification){
                 if(isset($specificationKeys[$specification['optionKey']])){
                     $specificationKeys[$specification['optionKey']] = array_unique(array_merge(
                         $specificationKeys[$specification['optionKey']], 
                         $specification['values']
                     ));
                 } else {
                     $specificationKeys[$specification['optionKey']] = $specification['values'];
                 }
             }             
         }
         
         // use that array to load the options
         $specificationOptions = $this->configurationManager->getDocumentManager()
           ->getRepository($this->configurationManager->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_SPECIFICATION_OPTION))
           ->createQueryBuilder()
           ->field('key')->in(array_keys($specificationKeys))
           ->field('filterable')->equals(true)
           ->getQuery()
           ->execute();
         
         // create our return array of ProductFilterSpecification available to display
         // on the filter html
         $return = array();
         foreach($specificationOptions as $k => $specificationOption) {
             $return[$k] = new ProductFilterSpecification($specificationOption);
             if(isset($specificationKeys[$specificationOption->getKey()])){
                 $return[$k]->setValues($specificationKeys[$specificationOption->getKey()]);   
             }
         }
         
         return $return;
     }
}
