<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

/**
 * LoginFormType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class LoginFormType extends AbstractType
{

    /**
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add('_username', 'email', array('label' => 'E-Mail', 'attr' => array('placeholder' => 'Enter your e-mail address')))
        ->add('_password', 'password', array())
        ;
    }

    public function getName()
    {
        return '';
    }
}
