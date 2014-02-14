<?php

namespace Spliced\Bundle\CommerceAdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ConfigurationCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ShippingCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\PaymentCompilerPass;
use Spliced\Component\Commerce\DependencyInjection\Compiler\ProductTypeCompilerPass;

class SplicedCommerceAdminBundle extends Bundle
{
	
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    
        $container->addCompilerPass(new ShippingCompilerPass());
        $container->addCompilerPass(new PaymentCompilerPass());
        $container->addCompilerPass(new ConfigurationCompilerPass());
		$container->addCompilerPass(new ProductTypeCompilerPass());
    
    }
    

}
