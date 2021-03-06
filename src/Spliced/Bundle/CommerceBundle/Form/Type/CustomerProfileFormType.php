<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CustomerProfileFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class CustomerProfileFormType extends AbstractType
{
    public function __construct()
    {
    }

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firstName')
            ->add('lastName')
            //->add('email')
            ->add('newsletterSubscriber')
          ;
        
    }

    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'data_class' => 'Spliced\Bundle\CommerceBundle\Entity\CustomerProfile'
        ));
    }

    /**
     *
     */
    public function getName()
    {
        return 'customer_profile';
    }

}
