<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * ConfigurationCompilerPass
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ConfigurationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('commerce.configuration')) {
            return;
        }
        
        $configurationInitializer = $container->getDefinition('commerce.configuration');

    	$services = $container->findTaggedServiceIds('commerce.configurable');
    	
        foreach ($services as $id => $attributes) {
            $configurationInitializer->addMethodCall('addConfigurableServiceId', array($id));
        }
 
    }
}