<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Security\Encryption;

use Spliced\Component\Commerce\Configuration\ConfigurableInterface;
use Spliced\Component\Commerce\Configuration\ConfigurationManager;

/**
 * EncryptionManager
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class EncryptionManager implements EncryptorInterface, ConfigurableInterface
{
    /** array */
    protected $options = array(
        'max_length' => 90,
        'cipher' => MCRYPT_RIJNDAEL_256,
        'cipher_mode' => MCRYPT_MODE_CFB,
        'key_length' => 32,
        'chunk_bytes' => 24,
    );

    /**
     * @param array $options
     */
    public function __construct(ConfigurationManager $configurationManager, array $options = array())
    {
        if (!function_exists('mcrypt_encrypt')) {
            throw new \RuntimeException('php-mcrypt library not installed.');
        }

        $this->configurationManager = $configurationManager;
        $this->options = array_merge($this->options,$options);
    }

    /**
     * @return string
     */
    public function encrypt($protectCode, $value)
    {
        $len = strlen($value);
        if ($len > $this->options['chunk_bytes']) {
            throw new \Exception("Length Greater Than Chunk Btyes");
        } elseif ($len < $this->options['chunk_bytes']) {
            $value = str_pad($value, $this->options['chunk_bytes']);
        }

        return base64_encode(mcrypt_encrypt($this->options['cipher'], $protectCode, base64_encode($value), $this->options['cipher_mode'], $this->getConfigurationManager()->get('commerce.security.encryption.iv')));
    }

    /**
     *
     * @return string
     */
    public function decrypt($protectCode, $encryptedValue)
    {
        $b64 = mcrypt_decrypt(
            $this->options['cipher'],
            $protectCode,
            base64_decode($encryptedValue),
            $this->options['cipher_mode'],
            $this->getConfigurationManager()->get('commerce.security.encryption.iv')
        );

        // We stored it base64 encoded to remove any numerical
        // patterns.
        $plain = base64_decode($b64);

        return rtrim($plain);
    }

    /**
     * generateIv
     */
    public function generateIv()
    {
        $size = mcrypt_get_iv_size($this->options['cipher'], $this->options['cipher_mode']);

        return mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigurationManager()
    {
        return $this->configurationManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.security.encryption';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        return array(
            'iv' => array(
                'type' => 'string',
                'value' => $this->generateIv(),
                'label' => 'Encryption IV',
                'help' => '',
                'group' => 'Security/Encryption',
                'position' => 1,
                'required' => true,
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->getConfigurationManager()->getByKeyExpression(sprintf('/^%s/',$this->getConfigPrefix()));
    }

    /**
     * {@inheritDoc}
     */
    public function getOption($key, $defaultValue = null)
    {
        return $this->getConfigurationManager()->get(sprintf('%s.%s',$this->getConfigPrefix(),$key),$defaultValue);
    }
}
