<?php

namespace Spliced\Component\Commerce\Payment\Phone;

use Spliced\Component\Commerce\Payment\Model\PaymentProvider;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\Order;

class PhonePayment extends PaymentProvider
{
    protected $name = 'phone';

    /**
     * {@inheritDoc}
     */
    public function process(OrderInterface $order)
    {
        $order->setOrderStatus($this->getOption('checkout_complete_status', Order::STATUS_PENDING));

        $order->getPayment()->setPaymentStatus(Order::STATUS_PENDING);
        
        return $order;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.payment.phone';
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
