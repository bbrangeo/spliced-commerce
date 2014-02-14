<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * CmsPageType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CmsPageType extends AbstractType
{
	/**
	 * Constructor
	 *
	 * @param ConfigurationManager $configurationManager
	 */
	public function __construct(ConfigurationManager $configurationManager)
	{
		$this->configurationManager = $configurationManager;
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
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('title')
            ->add('pageTitle', 'textarea', array('required' => false))
            ->add('pageLayout', 'textarea', array('required' => false))
            ->add('urlSlug')
            ->add('metaDescription', 'textarea', array('required' => false))
            ->add('metaKeywords', 'textarea', array('required' => false))
            ->add('content', 'textarea', array('attr' => array( 'class' => 'wysiwyg')))
            ->add('isActive');
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_cms_page';
    }
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' => $this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_CMS_PAGE),
    	));
    }
}