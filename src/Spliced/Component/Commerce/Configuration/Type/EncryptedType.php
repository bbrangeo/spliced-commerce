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
use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Security\Encryption\EncryptorInterface;

/**
 * EncryptedType
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class EncryptedType implements TypeInterface
{
    
    /**
     * Constructor
     * 
     * @param ConfigurationManager $configurationManager
     * @param EncryptorInterface $encryptionManager
     */
    public function __construct(ConfigurationManager $configurationManager, EncryptorInterface $encryptionManager)
    {
        $this->configurationManager = $configurationManager;
        $this->encryptionManager = $encryptionManager;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'encrypted';
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