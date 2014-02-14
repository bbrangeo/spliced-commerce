<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
/**
 * CustomerFactory
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerFactory extends FormLoginFactory
{
     protected function getListenerId()
    {
        return 'commerce.security.authentication.listener.customer';
    }
    
    /*public function getKey()
    {
        return 'form_login';
    }
    */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'commerce.security.authentication.provider.customer.'.$id;
        
        $container
            ->setDefinition($provider, new DefinitionDecorator('commerce.security.authentication.provider.customer'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, new Reference($userProviderId))
        ;
        return $provider;
    }
    
/*
    protected function createListener($container, $id, $config, $userProvider)
    {
        $listenerId = $this->getListenerId();
        
        $listener = new DefinitionDecorator($listenerId);
        $listener->replaceArgument(4, $id);
        $listener->replaceArgument(5, new Reference($this->createAuthenticationSuccessHandler($container, $id, $config)));
        $listener->replaceArgument(6, new Reference($this->createAuthenticationFailureHandler($container, $id, $config)));
        $listener->replaceArgument(7, array_intersect_key($config, $this->options));

        $listenerId .= '.'.$id;
        $container->setDefinition($listenerId, $listener);

        return $listenerId;
        
        $listenerId = parent::createListener($container, $id, $config, $userProvider);
        //die($listenerId);
        if (isset($config['csrf_provider'])) {
            $container
            ->getDefinition($listenerId)
            ->addArgument(new Reference($config['csrf_provider']))
            ;
        }
    
        return $listenerId;
    }*/
    
    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'commerce.security.authentication.entry_point.customer.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('commerce.security.authentication.entry_point.customer'))
            ->addArgument(new Reference('security.http_utils'))
            ->addArgument($config['login_path'])
            ->addArgument($config['use_forward'])
        ;

        return $entryPointId;
    }
}
