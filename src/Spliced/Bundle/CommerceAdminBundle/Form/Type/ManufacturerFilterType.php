<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

/**
 * ManufacturerFilterType
 *
 * @author Gassan Idriss <ghassani@gmail.com>
 */
class ManufacturerFilterType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
        $resolver->setDefaults(array(
            'data_class' => 'Spliced\Bundle\CommerceAdminBundle\Model\ListFilter'
        ));
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'commerce_manufacturer_filter';
    }
}
