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
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Spliced\Component\Commerce\Helper\UserAgent as UserAgentHelper;
use Spliced\Component\Commerce\Logger\Logger;
use Spliced\Component\Commerce\Model\UserInterface;

/**
 * UserEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class UserEventListener
{
    protected $securityContext;
    protected $em;

    /**
     * Constructor
     *
     * @param ConfigurationManager $configurationManager
     * @param $router
     * @param SecurityContext $securityContext
     * @param Session         $session
     * @param Logger   $logger
     */
    public function __construct(ConfigurationManager $configurationManager, $router, SecurityContext $securityContext, Session $session, Logger $logger)
    {
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->session = $session;
        $this->configurationManager = $configurationManager;
        $this->logger = $logger;
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
     * getLogger
     * 
     * @return Logger
     */
    protected function getLogger()
    {
        return $this->logger;
    }
    
    /**
     * getAffiliateManager
     * 
     * @return AffiliateManager
     */
    protected function getAffiliateManager()
    {
        return $this->affiliateManager;
    }
    
    /**
     * getSession
     * 
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * getRouter
     * 
     * @return Router
     */
    protected function getRouter()
    {
        return $this->router;
    }
    
    /**
     * getSecurityContext
     * 
     * @return SecutityContext
     */
    protected function getSecurityContext()
    {
         return $this->securityContext;
    }
    
    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->configurationManager->getEntityManager();    
    }
    
    /**
     * onSecurityInteractiveLogin
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        
    }

    /**
     * onKernelRequest
     */
    public function onKernelRequest(GetResponseEvent $event)
    {

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType() || ! $this->getSession()->getId()) {
            return; // "subrequest"
        }
         
        $requestedRoute = $event->getRequest()->get('_route');

        if ($this->securityContext->getToken() && $this->securityContext->isGranted('ROLE_USER') ) {
            $user = $this->securityContext->getToken()->getUser();
            
            if($user instanceof UserInterface) {
                // admin used lets get not continue
                return;
            }
            
            if(($user->getForceCollectEmail() || $user->getForcePasswordReset()) 
                && !in_array($requestedRoute, array('commerce_account_finalize_registration'))
                && !preg_match('/login\_check$/',$requestedRoute)){
                
                $event->setResponse(new RedirectResponse(
                    $this->router->generate('commerce_account_finalize_registration')
                ));
                return;
            }
        }
    }
    
    /**
     * onKernelTerminate
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        
    }

}
