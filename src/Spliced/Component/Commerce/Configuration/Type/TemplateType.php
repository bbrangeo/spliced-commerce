<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration\Type;

use Spliced\Component\Commerce\Model\ConfigDataInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Finder\Finder;

/**
 * TemplateType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TemplateType implements TypeInterface
{
    /**
     * Constructor
     * 
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'template';
    }
    
    /**
     * {@inheritDoc}
     */
    public function getApplicationValue(ConfigDataInterface $configData)
    {
        return $configData->getValue();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getDatabaseValue(ConfigDataInterface $configData)
    {
        return $configData->getValue();
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(ConfigDataInterface $configData, FormBuilderInterface $form)
    {
        $form->add($configData->getFormSafeKey(), 'choice', array(
            'label' => $configData->getLabel() ? $configData->getLabel() : null,
            'data' => $this->getApplicationValue($configData),
            'multiple' => false,
            'choices' => $this->getTemplates(),
        ));
    }
    
    /**
     * getTemplates
     * 
     * Iterate over each bundle Resources/views directory 
     * to get a list of available templates for the user to
     * choose from.
     */
    private function getTemplates()
    {
        if( isset($this->templates)) {
            return $this->templates;
        }

        $finder = new Finder();
        $return = array('SplicedCommerceBundle' => array());
        
        $frontendBundle = false;
        $backendBundle = false;
        foreach ($this->kernel->getBundles() as $bundle) {
            if (in_array($bundle->getName(), $this->getExcludedBundles())) {
                continue;
            }
            
            if ($bundle->getName() == 'SplicedCommerceBundle') {
                $frontendBundle = $bundle->getPath();
            } elseif ($bundle->getName() == 'SplicedCommerceAdminBundle') {
                $backendBundle = $bundle->getPath();
            }
            
            $path = $bundle->getPath().DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
            
            if (!file_exists($path) || ! is_dir($path)) {
                continue;
            }
            
            if(!isset($return[$bundle->getName()])){
                $return[$bundle->getName()] = array();
            }
            
            $iterator = $finder
            ->create()
            ->files()
            ->in($path);
            
            foreach ($iterator as $file) {
                $return[$bundle->getName()][$file->getRealpath()] = $bundle->getName().':'.str_replace($path, '', $file->getRealpath());
            }
        }
        
        if (!$frontendBundle && $backendBundle) {
            $frontendBundle = $backendBundle.'/../CommerceAdminBundle/Resources/views';
            
            $iterator = $finder
            ->create()
            ->files()
            ->in($frontendBundle);
                        
            foreach ($iterator as $file) {
                $return['SplicedCommerceBundle'][$file->getRealpath()] = 'SplicedCommerceBundle'.':'.str_replace($path, '', $file->getRealpath());
            }
        } else {
            throw new \RuntimeException('Could Not Find Frontend or Backend Bundles. Something went wrong.');
        }
        
        $this->templates = $return;
        
        unset($return);
        unset($bundlePaths);
        unset($fileLocator);
        
        return $this->templates;
    }
    
    /**
     * getExcludedBundles
     * 
     * @return array
     */
    private function getExcludedBundles()
    {
        return array(
            'FrameworkBundle',
            'SecurityBundle',
            'TwigBundle',
            'SwiftmailerBundle',
            'DoctrineBundle',
            'DoctrineMongoDBBundle',
            'WebProfilerBundle',
            'SensioDistributionBundle',
            'KnpPaginatorBundle',
        );
    }
    

}
