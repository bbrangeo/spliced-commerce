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
 * TypeInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface TypeInterface
{
    /**
     * getName
     * 
     * A unique string to identify the configuration field type
     * 
     * @return string
     */
    public function getName();
    
    /**
     * getApplicationValue
     *
     * Process a configuration field and return a value for the application
     *
     * @return string
     */
    public function getApplicationValue(ConfigDataInterface $configData);
    
    /**
     * getDatabaseValue
     *
     * Process a configuration field and return a value for the database
     *
     * @return string
     */
    public function getDatabaseValue(ConfigDataInterface $configData);
    
    /**
     * buildForm
     *
     * Process a configuration field and build upon a FormBuilderInterface
     *
     * @param ConfigDataInterface $configData
     * @param FormBuilderInterface $form
     * 
     * @return string
     */
    public function buildForm(ConfigDataInterface $configData, FormBuilderInterface $form);
}
