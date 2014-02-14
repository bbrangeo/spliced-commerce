<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Configuration;

/**
 * ConfigurableInterface
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
interface ConfigurableInterface
{

    /**
     * @return ConfigurationManager
     */
    public function getConfigurationManager();

    /**
     * getConfigPrefix
     *
     * @return string
     */
    public function getConfigPrefix();

    /**
     * getRequiredConfigurationFields
     *
     * @return array
     */
    public function getRequiredConfigurationFields();

    /**
     * getOptions
     * @return array
     */
    public function getOptions();

    /**
     * getOption
     * @param string $key
     *
     * @return mixed
     */
    public function getOption($key, $defaultValue = null);
}
