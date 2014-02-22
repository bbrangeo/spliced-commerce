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
 * Handles the filtering of products when viewing a collection of products.
 * Can be contextual to a specific category, or all products in general
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductFilterManager
{
    /* Available List Modes by Default */
    const DISPLAY_MODE_GRID = 'grid';
    const DISPLAY_MODE_LIST = 'list';

    /** Session Tag Constants */
    const SESSION_TAG_PER_PAGE         = 'commerce.product.filter.per_page';
    const SESSION_TAG_ORDER_BY         = 'commerce.product.filter.order_by';
    const SESSION_TAG_DISPLAY_MODE     = 'commerce.product.filter.display_mode';
    const SESSION_TAG_SPECIFICATIONS   = 'commerce.product.filter.specifications';
    const SESSION_TAG_MANUFACTURERS    = 'commerce.product.filter.manufacturers';
    
    /** CategoryInterface|null */
    protected $category = null;
    
    /** Collection|null */
    protected $specifications = null;
    
    /** Collection|null */
    protected $manufacturers = null;

    
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
         
        // get our base query
         if( $this->getCategory()) {
             $query = $productRepository->findByCategoryQuery($this->getCategory());
         } else {
             $query = $productRepository->createQueryBuilder('product');
         }
         
         // apply manufacturer filters
         if ($this->getCategory() && $this->getSelectedManufacturers($this->getCategory()->getId())) { 
            $query->field('manufacturer.id')->in($this->getSelectedManufacturers($this->getCategory()->getId()));
         }
         
         $selectedSpecifications = $this->getSelectedSpecifications($this->getCategory() ? $this->getCategory()->getId() : 0);
         
         // apply specification filters
         if ($selectedSpecifications) {
            $exp = $query->expr();
         	foreach ($selectedSpecifications as $selectedSpecificationId => $selectedSpecificationValues) {         	    
         	    $exp->addOr($query->expr()->field('specifications')->elemMatch(
         	          $query->expr()
         	            ->field('option.$id')->equals(new \MongoId($selectedSpecificationId))
         	            //->field('values')->in($query->expr()->in($selectedSpecificationValues))
         	            ->field('values')->in($selectedSpecificationValues)
         	            /*->where('function(){
         	                    if(this.values == undefined){
         	                        return true;
         	                    }   
         	                    print("sweetnesss");      	                     
         	                    return false;
         	            }')*/
         	      )
                ); 
             } 
             $query->addAnd($exp);
         } 
         
         return $query;
     }
     
     /**
      * prepareView
      * 
      * Pre-load specifications and manufacturers before 
      * rendering the view
      */
     public function prepareView()
     {
         $this->getAvailableSpecifications();
         $this->getAvailableManufacturers();
     }
     
     /**
      * getAvailableSpecifications
      * 
      * Finds available specifications available to the current
      * filter context.
      * 
      * @return array
      */
     public function getAvailableSpecifications()
     {
         if(isset($this->specifications) && !is_null($this->specifications)){
             return $this->specifications;
         }
         
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
         
         $this->specifications = $return;
         unset($return);
         return $this->specifications;
     }
     
     /**
      * getAvailableManufacturers
      *
      * Finds available manufacturers available to the current
      * filter context.
      * 
      * @return array
      */
     public function getAvailableManufacturers()
     {
         if(isset($this->manufacturers) && !is_null($this->manufacturers)){
             return $this->manufacturers;
         }
         
         // lets load, as an array, just all product specifications
         // matching the current criteria
         $products = $this->getPaginationQuery()
         ->select('manufacturer')
         ->hydrate(false)
         ->getQuery()
         ->execute();

         $manufacturerIds = array();
         foreach($products as $product) {
             if(isset($product['manufacturer']['$id'])){
                 $manufacturerIds[] = (string) $product['manufacturer']['$id'];
             }
         }
          
         $this->manufacturers = $this->configurationManager->getDocumentManager()
           ->getRepository($this->configurationManager->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_MANUFACTURER))
           ->createQueryBuilder()
           ->field('id')->in($manufacturerIds)
           ->getQuery()
           ->execute();

         return $this->manufacturers;
     }
     
     /**
      * getSelectedSpecifications
      * 
      * @return array
      */
     public function getSelectedSpecifications($categoryId)
     {
         return  $this->session->get(static::SESSION_TAG_SPECIFICATIONS.'.'.$categoryId, array());
     }     

     /**
      * setSelectedSpecifications
      * 
      * @param array $specifications
      * @param string $categoryId
      */
     public function setSelectedSpecifications(array $specifications, $categoryId)
     {
         $this->session->set(static::SESSION_TAG_SPECIFICATIONS.'.'.$categoryId, $specifications);
         return $this; 
     }
     
     /**
      * addSelectedSpecification
      * 
      * @param string $specificationId
      * @param string $value
      * @param string $categoryId
      */
     public function addSelectedSpecification($specificationId, $specificationValue, $categoryId)
     {
         $specifications = $this->getSelectedSpecifications($categoryId);
         if(!isset($specifications[$specificationId])){
             $specifications[$specificationId] = array($specificationValue);
         } else {
             $specifications[$specificationId][] = $specificationValue;
             $specifications[$specificationId] = array_unique($specifications[$specificationId]);
         }         
         $this->setSelectedSpecifications(array_unique($specifications), $categoryId);
         return $this;
     }
     
     /**
      * removeSelectedSpecification
      * 
      * @param string $specificationId
      * @param string $value
      * @param string $categoryId
      */
     public function removeSelectedSpecification($specificationId, $specificationValue, $categoryId)
     {
          
     }
     
     /**
      * getSelectedManufacturers
      * 
      * @return array
      */
     public function getSelectedManufacturers($categoryId)
     {
          return  $this->session->get(static::SESSION_TAG_MANUFACTURERS.'.'.$categoryId, array());
     }
     
     /**
      * setSelectedSpecifications
      * 
      * @param array $manufacturerIds
      * @param string $categoryId 
      */
     public function setSelectedManufacturers(array $manufacturerIds, $categoryId)
     {
         $this->session->set(static::SESSION_TAG_MANUFACTURERS.'.'.$categoryId, $manufacturerIds);
         return $this;
     }
      
     /**
      * addSelectedManufacturer
      * 
      * @param string $manufacturerId
      * @param string $categoryId
      */
     public function addSelectedManufacturer($manufacturerId, $categoryId)
     {
          $manufacturers = $this->getSelectedManufacturers($categoryId);
          if (!in_array($manufacturerId, $manufacturers)) {
              $manufacturers[] = $manufacturerId;
          }
          $this->setSelectedManufacturers($manufacturers, $categoryId);
          return $this;
     }
     
     /**
      * removeSelectedManufacturer
      * 
      * @param string $manufacturerId
      * @param string $categoryId
      */
     public function removeSelectedManufacturer($manufacturerId, $categoryId)
     {
         $manufacturers = $this->getSelectedManufacturers($categoryId);
         foreach ($manufacturers as $key => $_manufacturerId) {
             if ($_manufacturerId == $manufacturerId) {
                 unset($manufacturers[$key]);
             }
         }
         $this->setSelectedManufacturers($manufacturers, $categoryId);
         return $this;
     }
}