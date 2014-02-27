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

/**
 * EmailType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class EmailType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'email';
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
    
    }
}
