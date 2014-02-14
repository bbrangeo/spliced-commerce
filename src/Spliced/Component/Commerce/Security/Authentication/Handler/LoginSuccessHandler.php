<?php

namespace Spliced\Component\Commerce\Security\Authentication\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    protected $router;
    protected $security;

    public function __construct(RouterInterface $router, SecurityContext $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    /**
     *
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // redirect the user to where they were before the login process begun.
        $refererUrl = $request->headers->get('referer');
                
        $response = new RedirectResponse($refererUrl ? $refererUrl : $this->router->generate('commerce_account'));
        return $response;
    }

}
