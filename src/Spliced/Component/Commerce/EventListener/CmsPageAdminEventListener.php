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
use Spliced\Component\Commerce\Event as Events;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * CmsPageAdminEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CmsPageAdminEventListener
{
    
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
     * getDocumentManager
     * 
     * @return ObjectManager
     */
    protected function getDocumentManager()
    {
        return $this->getConfigurationManager()->getDocumentManager();
    }
    
    /**
     * onCmsPageSave
     * 
     * @param CmsPageEvent $event
     */
    public function onCmsPageSave(Events\CmsPageEvent $event)
    {
    	$page = $event->getCmsPage();
    	
    	$route = $this->getConfigurationManager()
    	  ->createDocument(ConfigurationManager::OBJECT_CLASS_TAG_ROUTE)
    	  ->setPage($page)
    	  ->setRequestPath($page->getUrlSlug())
    	  ->setTargetPath('SplicedCommerceBundle:Page:view')
    	  ->setOptions(array());

    	$this->getDocumentManager()->persist($route);

    	$page->setRoute($route);
    	
    	$this->getDocumentManager()->persist($page);
    	$this->getDocumentManager()->flush();

    }
    /**
     * onCmsPageUpdate
     *
     * @param CmsPageEvent $event
     */
    public function onCmsPageUpdate(Events\CmsPageEvent $event)
    {
    
    }
    
    /**
     * onCmsPageDelete
     *
     * @param CmsPageEvent $event
     */
    public function onCmsPageDelete(Events\CmsPageEvent $event)
    {
    
    }
}