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
use Spliced\Component\Commerce\Form\DataTransformer\ProductTypeaheadTransformer;
use Spliced\Component\Commerce\Repository\ProductRepository;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * ProductUpsaleType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ProductUpsaleType extends AbstractType
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
     * getObjectManager
     *
     * @return ObjectManager
     */
    protected function getObjectManager()
    {
        return $this->getConfigurationManager()->getDocumentManager();
    }
    
    /**
     * getProductRepository
     * 
     * @return ProductRepository
     */
     protected function getProductRepository()
     {
         return $this->getObjectManager()
           ->getRepository($this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT));
     }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $typeaheadTransformer = new ProductTypeaheadTransformer($this->getProductRepository());
        
        $builder->add($builder->create('product', 'text', array('required' => true))
                ->addModelTransformer($typeaheadTransformer));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_product_upsale';
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getConfigurationManager()->getDocumentClass(ConfigurationManager::OBJECT_CLASS_TAG_PRODUCT_UPSALE),
        ));
    }
}