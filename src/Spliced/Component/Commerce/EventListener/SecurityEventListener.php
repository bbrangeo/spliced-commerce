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
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Spliced\Component\Commerce\Security\LoginManager;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Model\CustomerAddressInterface;
use Spliced\Component\Commerce\Model\CustomerInterface;
use Spliced\Component\Commerce\Logger\Logger;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * SecurityEventListener
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */ 
class SecurityEventListener
{
    public function __construct(UserProviderInterface $userManager, LoginManager $loginManager, ConfigurationManager $configurationManager, EntityManager $em, \Swift_Mailer $mailer, EngineInterface $templating, Logger $logger)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->configurationManager = $configurationManager;
        $this->loginManager = $loginManager;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->logger = $logger;
    }

    /**
     * getEntityManager
     * 
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
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
     * getUserManager
     * 
     * @return UserProviderInterface
     */
    protected function getUserManager()
    {
        return $this->userManager;
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
     * getLoginManager
     * 
     * @return LoginManager
     */
    protected function getLoginManager()
    {
        return $this->loginManager;
    }
    
    /**
     * getMailer
     * 
     * @return \Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }
    
    /**
     * getTemplating
     * 
     * @return TwigEngine
     */
    protected function getTemplating()
    {
        return $this->templating;
    }
    
    /**
     * onRegistrationStart
     */
    public function onRegistrationStart(Events\RegistrationStartEvent $event)
    {

    }

    /**
     * onRegistrationComplete
     */
    public function onRegistrationComplete(Events\RegistrationCompleteEvent $event)
    {
        $customer = $event->getCustomer();

        $this->userManager->updatePassword($customer);

        $customer->addRole(CustomerInterface::ROLE_DEFAULT)
          ->setEnabled(true);

        if($event->requiresConfirmation()){// TODO this is currently not fully implemented
            $customer->setLocked(true) 
              ->setConfirmationToken(md5(rand(100,2000000)));
        }
                
        $this->em->persist($customer);

        $event->getDispatcher()->dispatch(
            Events\Event::EVENT_SECURITY_NEW_ACCOUNT_CREATED,
            new Events\NewAccountEvent($customer)
        );
        
        try {
            $this->em->flush();
            $this->loginManager->loginUser('main', $customer, null);
        } catch (\Exception $e) {
            $this->getLogger()->exception(sprintf('Exception During Registration Complete Event - %s - %s', 
                get_class($e), 
                $e->getMessage()
            ));
        }
    }

    /**
     * onNewAccountCreated
     * 
     * @param Events\NewAccountEvent $event
     */
    public function onNewAccountCreated(Events\NewAccountEvent $event)
    {
        // send out notification
        $notificationMessage = \Swift_Message::newInstance()
        ->setSubject($this->getConfigurationManager()->get('commerce.sales.email.new_account.subject'))
        ->setFrom($this->getConfigurationManager()->get('commerce.store.email.noreply'))
        ->setTo($event->getUser()->getEmail())
        ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.new_account', 'SplicedCommerceBundle:Email:new_account.html.twig'), array(
            'user' => $event->getUser()
        )), 'text/html')
        ->addPart($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.new_account_plain', 'SplicedCommerceBundle:Email:new_account.txt.twig'), array(
            'user' => $event->getUser()
        )), 'text/plain')
        ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.bounced'));

        $this->getMailer()->send($notificationMessage);
    }
    
    /**
     * onLoginSuccess
     */
    public function onLoginSuccess(Events\LoginRegisterStartEvent $event)
    {

    }

    /**
     * onPayPalLogin
     */
    public function onPayPalLogin(Events\PayPalLoginEvent $event)
    {
        $customer = $event->getUser();
        $payPalProfile = $event->getPayPalProfile();
        $profile = $customer->getProfile();

        if (!$customer->hasRole('ROLE_PAYPAL') ) {
            $customer->addRole('ROLE_PAYPAL');
        }

        if (!$profile->getFirstName()||!$profile->getLastName()) {
            $profile->setFirstName($payPalProfile['result']['given_name'])
            ->setLastName($payPalProfile['result']['family_name']);
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onPayPalLoginCreateUser
     */
    public function onPayPalLoginCreateUser(Events\PayPalLoginEvent $event)
    {
        $customer         = $event->getUser();
        $payPalProfile  = $event->getPayPalProfile();
        $profile         = $customer->getProfile();

        $profile->setFirstName($payPalProfile['result']['given_name'])
        ->setLastName($payPalProfile['result']['family_name']);

        // if we get an address back, lets add it for the customer
        // to easily select it during checkout
        if (isset($payPalProfile['result']['address'])) {
            $addressClass = $this->configurationManager->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CUSTOMER_ADDRESS);
            $customerAddress = new $addressClass;

            if (!$customerAddress instanceof CustomerAddressInterface) {
                throw new \RuntimeException(sprintf('Customer address must be instance of CustomerAddressInterface, but got `%s`',get_class($customerAddress)));
            }

            $customerAddress->setCustomer($customer)
              ->setFirstName($payPalProfile['result']['given_name'])
              ->setLastName($payPalProfile['result']['family_name'])
              ->setAddressLabel('PayPal Address')
              ->setAddress($payPalProfile['result']['address']['street_address'])
              ->setCity($payPalProfile['result']['address']['locality'])
              ->setState($payPalProfile['result']['address']['region'])
              ->setZipcode($payPalProfile['result']['address']['postal_code'])
              ->setCountry($payPalProfile['result']['address']['country']);

            $customer->addAddress($customerAddress);
        }

        $customer->setProfile($profile);
    }

    /**
     * onFacebookLogin
     */
    public function onFacebookLogin(Events\FacebookLoginEvent $event)
    {
        $customer = $event->getUser();
        $facebookProfile = $event->getFacebookProfile();

        if ($customer->getProfile() && ! $customer->getProfile()->getFacebookId()) {
            $customer->getProfile()->setFacebookId($facebookProfile['id']);
        }

        if (!$customer->hasRole('ROLE_FACEBOOK')) {
            $customer->addRole('ROLE_FACEBOOK');
        }

        if (!$customer->getProfile()->getFirstName()) {
            $customer->getProfile()->setFirstName($facebookProfile['first_name']);
        }

        if (!$customer->getProfile()->getLastName()) {
            $customer->getProfile()->setLastName($facebookProfile['last_name']);
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onFacebookLoginCreateUser
     */
    public function onFacebookLoginCreateUser(Events\FacebookLoginEvent $event)
    {
        $user = $event->getUser();
        $facebookProfile = $event->getFacebookProfile();

        $user->getProfile()->setFacebookId($facebookProfile['id'])
        ->setFirstName($facebookProfile['first_name'])
        ->setLastName($facebookProfile['last_name']);
    }

    /**
     * onTwitterLogin
     */
    public function onTwitterLogin(Events\TwitterLoginEvent $event)
    {
        $customer = $event->getUser();
        $twitterProfile = $event->getTwitterProfile();

        if (!$customer->getProfile()->getTwitterId()) {
            $customer->getProfile()->setTwitterId($twitterProfile['user_id']);
        }

        if (!$customer->hasRole('ROLE_TWITTER')) {
            $customer->addRole('ROLE_TWITTER');
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onTwitterLogin
     */
    public function onTwitterLoginCreateUser(Events\TwitterLoginEvent $event)
    {
        $customer = $event->getUser();
        $twitterProfile = $event->getTwitterProfile();

        $customer->getProfile()->setTwitterId($twitterProfile['user_id']);
    }

    /**
     * onGoogleLogin
     */
    public function onGoogleLogin(Events\GoogleLoginEvent $event)
    {

        $customer = $event->getUser();
        $googleProfile = $event->getGoogleProfile();

        if (!$customer->getProfile()->getGoogleId()) {
            $customer->getProfile()->setGoogleId($googleProfile['result']['id']);
        }

        if (!$customer->hasRole('ROLE_GOOGLE')) {
            $customer->addRole('ROLE_GOOGLE');
        }

        $customer->setLastLogin(new \DateTime());
    }

    /**
     * onGoogleLoginCreateUser
     */
    public function onGoogleLoginCreateUser(Events\GoogleLoginEvent $event)
    {

        $customer = $event->getUser();
        $googleProfile = $event->getGoogleProfile();

        $customer->getProfile()->setGoogleId($googleProfile['result']['id'])
        ->setFirstName($googleProfile['result']['given_name'])
        ->setLastName($googleProfile['result']['family_name']);

    }

    
    /**
     * onPasswordReset
     */
    public function onPasswordReset(Events\PasswordResetEvent $event)
    {
        $user = $event->getUser();
        
        $user->setPlainPassword($event->getNewPassword())
        ->setConfirmationToken(null);
        
        $this->getUserManager()->updatePassword($user);
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    
    /**
     * onPasswordResetRequest
     */
    public function onPasswordResetRequest(Events\PasswordResetRequestEvent $event)
    {
        $user = $event->getUser();
        
        $this->getUserManager()->updateConfirmationToken($user);
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        $notificationMessage = \Swift_Message::newInstance()
        ->setSubject($this->getConfigurationManager()->get('commerce.store.email.password_reset.subject'))
        ->setFrom($this->getConfigurationManager()->get('commerce.store.email.from'))
        ->setTo($user->getEmail())
        ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.password_reset', 'SplicedCommerceBundle:Email:password_reset.html.twig'), array(
            'user' => $user,
        )), 'text/html')
        ->addPart($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.password_reset_plain', 'SplicedCommerceBundle:Email:password_reset.txt.twig'), array(
            'user' => $user,
        )), 'text/plain')
        ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.bounced'));
        
        $this->getMailer()->send($notificationMessage);
    }
    
    /**
     * onForcePasswordReset
     */
    public function onForcePasswordReset(Events\ForcePasswordResetEvent $event)
    {
        $user = $event->getUser();
        
        $user->setPlainPassword($event->getNewPassword())
        ->setConfirmationToken(null)
        ->setForcePasswordReset(false);
        
        $this->getUserManager()->updatePassword($user);
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    
    /**
     * onForcePasswordResetRequest
     */
    public function onForcePasswordResetRequest(Events\ForcePasswordResetRequestEvent $event)
    {
        $user = $event->getUser();
        
        $this->getUserManager()->updateSalt($user);
        $this->getUserManager()->updateConfirmationToken($user);
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        $notificationMessage = \Swift_Message::newInstance()
        ->setSubject($this->getConfigurationManager()->get('commerce.store.email.force_password_reset.subject'))
        ->setFrom($this->getConfigurationManager()->get('commerce.store.email.from'))
        ->setTo($user->getEmail())
        ->setBody($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.force_password_reset', 'SplicedCommerceBundle:Email:force_password_reset.html.twig'), array(
            'user' => $user,
        )), 'text/html')
        ->addPart($this->getTemplating()->render($this->getConfigurationManager()->get('commerce.template.email.force_password_reset_plain', 'SplicedCommerceBundle:Email:force_password_reset.txt.twig'), array(
            'user' => $user,
        )), 'text/plain')
        ->setReturnPath($this->getConfigurationManager()->get('commerce.store.email.bounced'));
        
        $this->getMailer()->send($notificationMessage);
    }
}