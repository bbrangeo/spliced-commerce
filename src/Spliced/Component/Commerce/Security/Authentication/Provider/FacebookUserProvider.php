<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\Authentication\Provider;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Spliced\Component\Commerce\Security\Authentication\Token\FacebookUserToken;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\ORM\NoResultException as UsernameNotFoundException;
use Spliced\Component\Commerce\Event as Events;

/**
 * FacebookUserProvider
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class FacebookUserProvider implements AuthenticationProviderInterface
{
    /**
     * @var \BaseFacebook
     */
    protected $facebook;
    protected $providerKey;
    protected $userProvider;
    protected $userChecker;
    protected $dispatcher;

    /**
     * @param string                        $providerKey
     * @param BaseFacebook                  $facebook
     * @param UserProviderInterface         $userProvider
     * @param UserCheckerInterface          $userChecker
     * @param ContainerAwareEventDispatcher $dispatcher
     */
    public function __construct($providerKey, \BaseFacebook $facebook, UserProviderInterface $userProvider, UserCheckerInterface $userChecker, EventDispatcher $dispatcher)
    {
        if (!$userProvider instanceof UserProviderInterface) {
            throw new \InvalidArgumentException('The $userProvider must implement UserManagerInterface if $createIfNotExists is true.');
        }

        $this->providerKey = $providerKey;
        $this->facebook = $facebook;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(TokenInterface $token)
    {

        if (!$this->supports($token)) {
            return null;
        }

        $user = $token->getUser();

        if ($user instanceof UserInterface) {
            $this->userChecker->checkPostAuth($user);
            $newToken = new FacebookUserToken($this->providerKey, $user, $user->getRoles(), $token->getAccessToken());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        if (!is_null($token->getAccessToken())) {
              $this->facebook->setAccessToken($token->getAccessToken());
        }

        if ($uid = $this->facebook->getUser()) {
            $newToken = $this->createAuthenticatedToken($uid, $token->getAccessToken());
            $newToken->setAttributes($token->getAttributes());

            return $newToken;
        }

        throw new AuthenticationException('The Facebook user could not be retrieved from the session.');
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof FacebookUserToken && $this->providerKey === $token->getProviderKey();
    }

    /**
     * {@inheritDoc}
     */
    protected function createAuthenticatedToken($uid, $accessToken = null)
    {
        if (null === $this->userProvider) {
            return new FacebookUserToken($this->providerKey, $uid, array(), $accessToken);
        }

        try {
            $userProfile = $this->facebook->api('/me');

            $user = $this->userProvider->getRepository()->findOneByFacebookIdOrEmail($uid, $userProfile['email']);

            $this->userChecker->checkPostAuth($user);

            $this->dispatcher->dispatch(
                Events\Event::EVENT_SECURITY_FACEBOOK_LOGIN, 
                new Events\FacebookLoginEvent($user, $userProfile)
            );

            $this->userProvider->update($user);

        } catch (UsernameNotFoundException $e) {
            $user = $this->createUser($userProfile);
        } catch (\FacebookApiException $e) {
            throw new AuthenticationException('Could Not Get User Facebook Details. Was permission granted?');
        }

        if (!$user instanceof UserInterface) {
            throw new AuthenticationException('User provider did not return an implementation of user interface.');
        }

        return new FacebookUserToken($this->providerKey, $user, $user->getRoles(), $accessToken);
    }

    /**
     * createUser
     *
     * @param array $userProfile
     */
    public function createUser(array $userProfile)
    {
        $className = $this->userProvider->getClass();
        $user = new $className;

        $user->setEmail($userProfile['email'])
          ->setPlainPassword(md5(uniqid(mt_rand(), true)))
          ->setEnabled(true)
          ->addRoles(array('ROLE_FACEBOOK','ROLE_USER'));

        $this->dispatcher->dispatch(
            Events\Event::EVENT_SECURITY_FACEBOOK_LOGIN_CREATE_USER, 
            new Events\FacebookLoginEvent($user, $userProfile)
        );

        return $this->userProvider->create($user);
    }

}
