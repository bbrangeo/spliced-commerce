<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\User;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Spliced\Bundle\CommerceBundle\Entity\Customer as User;
use Doctrine\ORM\EntityManager;
use Spliced\Component\Commerce\Model\CustomerProfileInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Spliced\Component\Commerce\Event as Events;

/**
 * CustomerUserManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerUserManager implements UserProviderInterface
{
    /**
    * Constructor.
    *
    * @param EncoderFactoryInterface $encoderFactory
    */
    public function __construct(ConfigurationManager $configurationManager, EncoderFactoryInterface $encoderFactory, EntityManager $em, EventDispatcher $eventDispatcher)
    {
        $this->configurationManager = $configurationManager;
        $this->encoderFactory = $encoderFactory;
        $this->repository = $em->getRepository('SplicedCommerceBundle:Customer');
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create($user)
    {
        if (! $user instanceof SecurityUserInterface) {
            $user = new $this->getClass();
        }

        $this->updatePassword($user);

        $this->em->persist($user);

        $this->em->flush();

        $this->eventDispatcher->dispatch(
            Events\Event::EVENT_SECURITY_NEW_ACCOUNT_CREATED,
            new Events\NewAccountEvent($user)
        );
        
        return $user;
    }

    public function update(SecurityUserInterface $user/*, CustomerProfileInterface $profile = null*/)
    {
        /*if (!is_null($profile)) {
            $this->em->persist($profile);
        }      */
        $this->em->persist($user);

        $this->em->flush();

        return $user;
    }
    /*
     *
     */
    public function updatePassword(SecurityUserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }
    
    /*
     *
    */
    public function updateSalt(SecurityUserInterface $user)
    {
        if(!$user->getSalt()){
            $user->setSalt(md5($user->getEmail()));
        }
    }

    /*
     *
    */
    public function updateConfirmationToken(SecurityUserInterface $user)
    {
        $user->setConfirmationToken(md5(rand(1000,100000)));
    }
    
    /**
     *
     */
    public function loadUserByUsername($username)
    {
        return $this->loadUserByEmail($username);
    }

    /**
     *
     */
    public function loadUserByEmail($username)
    {
        return $this->repository->findOneByEmail($username);
    }

    /**
     *
     */
    public function loadUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     *
     */
    public function loadUserByConfirmationToken($token)
    {
        return $this->repository->findOneByConfirmationToken($token);
    }

    /**
     * refreshUser
     *
     * @param  SecurityUserInterface $user
     * @return SecurityUserInterface
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        $class = $this->getClass();
        if (!$user instanceof $class) {
            throw new UnsupportedUserException('Account is not supported.');
        }

        if (!$user instanceof SecurityUserInterface) {
            throw new UnsupportedUserException(sprintf('Expected an instance of use Symfony\Component\Security\Core\User\UserInterface, but got "%s".', get_class($user)));
        }

        $refreshedUser = $this->repository->findOneById($user->getId());

        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $refreshedUser;
    }

    /**
     * getEncoder
     */
    protected function getEncoder(SecurityUserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }

    /**
     * supportsClass
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }

    /**
     * getClass
     */
    public function getClass()
    {
        return $this->getConfigurationManager()->getEntityClass(ConfigurationManager::OBJECT_CLASS_TAG_CUSTOMER);
    }

    /**
     * getRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
    /**
     * getConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * getEntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
}
