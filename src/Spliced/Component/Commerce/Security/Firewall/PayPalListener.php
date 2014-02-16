<?php

namespace Spliced\Component\Commerce\Security\Firewall;

use Spliced\Component\Commerce\Security\Authentication\Token\PayPalUserToken;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\HttpFoundation\Request;

/**
* Paypal authentication listener.
*/

class PayPalListener extends AbstractAuthenticationListener
{
    /**
     * {@inheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        return $this->getAuthenticationManager()
          ->authenticate(new PayPalUserToken('', array(), $request->query->get('code'), null));
    }
    
    /**
     * getAuthenticationManager
     * 
     * @return AbstractAuthenticationListener
     */
    protected function getAuthenticationManager()
    {
        return $this->authenticationManager;
    }
}
