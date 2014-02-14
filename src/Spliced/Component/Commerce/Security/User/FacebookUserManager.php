<?php

namespace Spliced\Component\Commerce\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookUserManager implements UserProviderInterface
{

    protected $facebook;
    protected $userProvider;

    /**
     * Constructor.
     *
     * @param BaseFacebook          $facebook
     * @param UserProviderInterface $userProvider
     */
    public function __construct(\BaseFacebook $facebook, UserProviderInterface $userProvider)
    {
        $this->facebook = $facebook;
        $this->userProvider = $userProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        return $this->userProvider->getRepository()->findOneByFacebookId($facebookId);
    }

    /**
     * refreshUser
     *
     * @param  SecurityUserInterface $user
     * @return SecurityUserInterface
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        return $this->userProvider->refreshUser($user);
    }

    /**
     *
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }

    /**
     *
     */
    public function getClass()
    {
        return 'Spliced\Bundle\CommerceBundle\Entity\Customer';
    }

    /**
     *
     */
    public function createUser(array $facebookData)
    {
        $className = $this->getClass();
        $user = new $className;

        $user->setEmail($facebookData['email'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->addRoles(array('ROLE_FACEBOOK','ROLE_USER'));

        $profile = $user->getProfile();

        $profile->setFacebookId($facebookData['id'])
          ->setFirstName($facebookData['first_name'])
          ->setLastName($facebookData['last_name']);

        $user->setProfile($profile);

        return $this->userProvider->create($user);

    }

}
