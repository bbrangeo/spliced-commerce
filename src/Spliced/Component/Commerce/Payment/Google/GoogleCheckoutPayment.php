<?php
/**
 * THIS IS NO LONGER USED BY GOOGLE OR IS BEING DISCONTINUED JUST A PLACEHOLDER FOR OLD
 * ORDER COMPATIBILITY
 */
namespace Spliced\Component\Commerce\Payment\Google;

use Spliced\Component\Commerce\Payment\Model\PaymentProvider;
use Spliced\Component\Commerce\Payment\Model\RemotelyProcessedPaymentProviderInterface;

class GoogleCheckoutPayment extends PaymentProvider /*implements RemotelyProcessedPaymentProviderInterface */{

    protected $name = 'google_checkout';

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.payment.google.checkout';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        return array_merge(parent::getRequiredConfigurationFields(),array(
        ));
    }
}
