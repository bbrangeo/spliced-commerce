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
use Spliced\Component\Commerce\Event\CategorySaveEvent;
use Spliced\Component\Commerce\Event\CategoryUpdateEvent;
use Spliced\Component\Commerce\Model\CategoryInterface;
use Spliced\Component\Commerce\Model\RouteInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * CategoryAdminEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CategoryAdminEventListener
{
    protected $configurationManager;

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param ObjectManager        $om
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
     *  getDocumentManager
     *  
     *  @return ObjectManager
     */
    protected function getDocumentManager()
    {
        return $this->configurationManager->getDocumentManager();
    } 
    
    /**
     * onCategorySave
     * 
     * @param CategorySaveEvent $event
     */
    public function onCategorySave(CategorySaveEvent $event)
    {
        $category = $event->getCategory();

        $this->getDocumentManager()->persist($category);
        $this->getDocumentManager()->flush();
        
        /*if($category->getParent()) {
            $this->updateCategoryTree($category->getParent(), true, true, false);
        }*/
        
        $this->getDocumentManager()->flush();
    }

    /**
     * onCategoryUpdate
     * 
     * @param CategoryUpdateEvent $event
     */
    public function onCategoryUpdate(CategoryUpdateEvent $event)
    {
        $category = $event->getCategory();

        #$this->updateCategoryTree($category, false, true, false);        
        
        $this->getDocumentManager()->persist($category);

        $this->getDocumentManager()->flush();
        
    }
    
    /**
     * createRoute
     * 
     * @param CategoryInterface $category
     * 
     * @return RouteInterface
     */
    private function createRoute(CategoryInterface $category)
    {
        return $this->getConfigurationManager()->createDocument(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE)
          ->setCategory($category);
    }
    
    /**
     * updateCategoryTree
     * 
     * Recursive function to handle updating children relations
     * 
     * @param CategoryInterface $category
     * @param bool $persist
     * @param bool $persistParent
     * @param bool $isRecursiveCall
     */
    private function updateCategoryTree(CategoryInterface $category, $persist = false, $persistParent = true, $isRecursiveCall = false)
    {
        // update and children
        $children = $this->getDocumentManager()
        ->getRepository($this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_CATEGORY))
        ->createQueryBuilder()
        ->field('parent')->exists(true)
        ->field('parent.id')->equals($category->getId())
        ->getQuery()
        ->execute();
        
        if(count($children)) {
            $category->setChildren($children->toArray());
        }
        
        if($category->getParent()) {
            $this->updateCategoryTree($category->getParent(), false, $persistParent, true);
        }
        
        if(true === $persist && false === $isRecursiveCall){
            $this->getDocumentManager()->persist($category);
        } else if(true === $persistParent && $isRecursiveCall === true){
            $this->getDocumentManager()->persist($category);
        }
        
    }
}
