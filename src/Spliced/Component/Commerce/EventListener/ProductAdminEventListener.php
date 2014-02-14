<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace  Spliced\Component\Commerce\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Event\ProductViewEvent;
use Spliced\Component\Commerce\Event\ProductSaveEvent;
use Spliced\Component\Commerce\Event\ProductUpdateEvent;
use Spliced\Component\Commerce\Event\ProductDeleteEvent;
use Spliced\Component\Commerce\Event\ProductCategoryAddEvent;
use Spliced\Component\Commerce\Event\ProductCategoryDeleteEvent;
use Spliced\Component\Commerce\Event\ProductImageAddEvent;
use Spliced\Component\Commerce\Event\ProductImageDeleteEvent;
use Spliced\Component\Commerce\Event\Event as EventList;
use Spliced\Component\Commerce\Model\ProductInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * ProductAdminEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductAdminEventListener
{
    /** @var ConfigurationManager */
    protected $configurationManager;

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     */
    public function __construct(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }
    
    /**
     * getConfigurationManager
     *
     * @return ConfigurationManager
     */
    protected function getConfigurationManager()
    {
        return $this->configurationManager;
    }
    /**
     *  getObjectManager
     *
     *  @return ObjectManager
     */
    protected function getObjectManager()
    {
        return $this->getConfigurationManager()->getDocumentManager();
    }
        
    /**
     * onProductSave
     *
     * @param ProductSaveEvent $event
     */
    public function onProductSave(ProductSaveEvent $event)
    {
        $product = $event->getProduct();
        
        $route = $this->getConfigurationManager()->createDocument(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE)
        ->setRequestPath($product->getUrlSlug())
        ->setTargetPath('SplicedCommerceBundle:Product:view')
        ->setProduct($product)
        ->setOptions(array());
        
        $this->getObjectManager()->persist($route);
        
        $product->setRoute($route);
        
    }
    
    /**
     * onProductUpdate
     *
     * @param ProductUpdateEvent $event
     */
    public function onProductUpdate(ProductUpdateEvent $event)
    {          
        $product = $event->getProduct();
       
        // check for new images
        if($product->getImages()){
            foreach($product->getImages() as $image){
                if($image->getUploadedImage()){
                    $event->getDispatcher()->dispatch(
                        EventList::EVENT_PRODUCT_IMAGE_ADD,
                        new ProductImageAddEvent($product, $image)
                    );
                }
            }
        }
        
        // handle product route
        if($product->getRoute()){
        	$route = $this->getObjectManager()
        	->getRepository($this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE))
        	->findOneById($product->getRoute()->getId());
        } else {
        	// no route, we need to create one
        	$route = $this->createRoute($product)
        	->setTargetPath('SplicedCommerceBundle:Product:view')
        	->setOptions(array());
        } 
        $route->setRequestPath($product->getUrlSlug());
        $this->getObjectManager()->persist($route);
                      
    }
    
    /**
     * onProductDelete
     *
     * @param ProductDeleteEvent $event
     */
    public function onProductDelete(ProductDeleteEvent $event)
    {
        
        $product = $event->getProduct();
        
        // remove the route
        if($product->getRoute()){
            $route = $this->getObjectManager()
            ->getRepository($this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE))
            ->findOneById($product->getRoute()->getId());
            
            if($route){
                $this->getObjectManager()->remove($route);
            }
        }
        
        
        // remove images on filesystem
        foreach($product->getImages() as $image){
            $event->getDispatcher()->dispatch(
        		Event::EVENT_PRODUCT_IMAGE_DELETE,
            	new ProductImageDeleteEvent($product, $image)
            	
            );
        }
        
    }
    
    /**
     * onProductCategoryAdd
     *
     * @param ProductCategoryAddEvent $event
     */
    public function onProductCategoryAdd(ProductCategoryAddEvent $event)
    {
        $product = $event->getProduct();
        $category = $event->getProductCategory();
        
        if(!$product->hasCategory($category)){
        	$product->addCategory($category);
        }
                
        $this->getObjectManager()->persist($product);        
        $this->getObjectManager()->flush();
    }
    
    /**
     * onProductCategoryAdd
     *
     * @param ProductCategoryAddEvent $event
     */
    public function onProductCategoryDelete(ProductCategoryDeleteEvent $event)
    {

        $this->getObjectManager()->remove($event->getProductCategory());
    
        $this->getObjectManager()->flush();
    }
    
    
    /**
     * onProductImageAdd
     *
     * @param ProductImageAddEvent $event
     */
    public function onProductImageAdd(ProductImageAddEvent $event)
    {    	
        $product = $event->getProduct();
        $productImage = $event->getProductImage();
        $uploadedImage = $productImage->getUploadedImage();

        $productImage->setFilePath(DIRECTORY_SEPARATOR.'catalog'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR)
          ->setFileName(sprintf('%s_%s', $product->getId(),$uploadedImage->getClientOriginalName()))
          ->setFileType($uploadedImage->getClientMimeType())
          ->setFileMd5(md5_file($uploadedImage->getRealPath()));
        
        $targetDir = $this->getConfigurationManager()->get('commerce.path.web').$productImage->getFilePath();
  
        if(!is_dir($targetDir)){
            mkdir($productImage->getFilePath());
        }
        
        $moveStatus = $uploadedImage->move($targetDir, $productImage->getFileName());
    }
    
    /**
     * onProductImageDelete
     *
     * @param ProductImageDeleteEvent $event
     */
    public function onProductImageDelete(ProductImageDeleteEvent $event)
    {
    
        $product = $event->getProduct();
        $productImage = $event->getProductImage();
                
        // remove the file
        $filePath = sprintf('%s/%s', $this->getConfigurationManager()->get('commerce.path.web'), $productImage->getFullPath());
       
        if(file_exists($filePath) && !is_dir($filePath)){
            @unlink($filePath);
        }

        $product->removeImage($productImage);
      

    }

    /**
     * createRoute
     * 
     * @param ProductInterface $product
     * 
     * @return RouteInterface
     */
    private function createRoute(ProductInterface $product)
    {
    	return $this->getConfigurationManager()->createDocument(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE)
    	->setProduct($product);
    }
    
       
    
}
