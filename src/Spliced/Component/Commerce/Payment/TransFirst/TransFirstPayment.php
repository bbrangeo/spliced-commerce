<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Payment\TransFirst;

use Spliced\Component\Commerce\Configuration\ConfigurationManager;
use Spliced\Component\Commerce\Payment\Model\PaymentProvider;
use Spliced\Component\Commerce\Payment\Model\CreditCardPaymentProviderInterface;
use Spliced\Component\Commerce\Model\OrderInterface;
use Spliced\Component\Commerce\Model\Order;
use Spliced\Component\Commerce\Payment\PaymentDeclinedException;
use Spliced\Component\Commerce\Payment\PaymentErrorException;
use Spliced\Component\TransFirst\Client;
use Spliced\Component\Commerce\Helper\Order as OrderHelper;

use Spliced\Component\Commerce\Security\Encryption\EncryptionManager;

/**
 * TransFirstPayment
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class TransFirstPayment extends PaymentProvider implements CreditCardPaymentProviderInterface
{
    protected $encryptionManager;
    
    /**
     * {@inheritDoc}
     */
    
    public function __construct(ConfigurationManager $configurationManager, OrderHelper $orderHelper)
    {
        parent::__construct($configurationManager, $orderHelper);
        
        $this->encryptionManager = new EncryptionManager ($configurationManager);
        
        $this->client = new Client(
          $this->getOption('merchant_id'),
          $this->getOption('reg_key'),
          $this->getOption('test_mode')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function process(OrderInterface $order)
    {
        die("TODO"); // havent implemented
        
        $payment    = $order->getPayment();
        $creditCard = $payment->getCreditCard();

        $amountToCharge = $this->getOrderHelper()->getOrderTotal($order);
        //$amountToCharge = 2;
        $creditCardNumber = $creditCard->getCardNumber();

      
        if ($creditCard->isEncrypted()) { // decrypt if needed
            $creditCardNumber = $this->getEncryptionManager()
                ->decrypt($order->getProtectCode(), $creditCardNumber);
        }
       
        
        $clientRequest = $this->getClient()->createRequest(Client::REQUEST_AUTH_CAPTURE)
          ->setFirstName($order->getBillingFirstName())
          ->setLastName($order->getBillingLastName())
          ->setAddress(trim(sprintf('%s %s',$order->getBillingAddress(),$order->getBillingAddress2())))
          ->setCity($order->getBillingCity())
          ->setState($order->getBillingState())
          ->setZipcode($order->getBillingZipcode())
          ->setAmount($amountToCharge)
          ->setInvoiceNumber($order->getOrderNumber() ? $order->getOrderNumber() : $order->getId())
          ->setEmail($order->getEmail())
          ->setCreditCard($creditCardNumber)
          ->setExpirationDate($creditCard->getCardExpiration())
          ->setCvv2($creditCard->getCardCvv());
        
        $clientResponse = $this->getClient()
          ->doRequest($clientRequest);

        /** create a memo for purpose of logging and keeping track of transactions via API */
        $paymentMemo = $this->configurationManager
        ->createEntity(ConfigurationManager::OBJECT_CLASS_TAG_ORDER_PAYMENT_MEMO)
        ->setPayment($payment)
        ->setPreviousStatus($payment->getPaymentStatus())
        ->setCreatedBy('authorize')
        ->setMemoData(serialize($clientResponse->getResponseArray()));

        if (!$clientResponse->isSuccess()) { // handle bad responses or declined payments
            
            // handle declined differently, and do it first
            if ($clientResponse->isDeclined()) {
                $paymentMemo->setMemo(sprintf('Credit Card Ending in %s DECLINED for $%s',
                  $creditCard->getLastFour(),
                  $amountToCharge
                ))
                ->setChangedStatus(Order::STATUS_DECLINED);

                $payment->addMemo($paymentMemo)
                  ->setPaymentStatus(Order::STATUS_DECLINED);
 
                throw new PaymentDeclinedException($order, $clientResponse->getMessage());
            }

            $paymentMemo->setMemo(sprintf('Credit Card Ending in %s ERROR for $%s',
              $creditCard->getLastFour(),
              $amountToCharge
            ))
            ->setChangedStatus(Order::STATUS_INCOMPLETE);
    
            $payment->addMemo($paymentMemo)
              ->setPaymentStatus(Order::STATUS_INCOMPLETE);
               
           throw new PaymentErrorException($order, $clientResponse->getMessage());   
        }
        
        
        // we had success, lets make a payment memo stating that
        $paymentMemo->setChangedStatus($this->getOption('checkout_complete_status'))
          ->setAmountPaid($clientResponse->getAmount())
          ->setMerchantTransactionId($clientResponse->getAuthorizationCode())
          ->setMemo(sprintf('Credit Card Ending in %s APPROVED for $%s',
            $creditCard->getLastFour(),
            $clientResponse->getAmount()
          ));

        $payment->addMemo($paymentMemo)
          ->setPaymentStatus(Order::STATUS_COMPLETE);

        $order->setOrderStatus($this->getOption('checkout_complete_status'));

        if (!$this->savesCreditCard()) {
            $creditCard->setCardNumber(null)
              ->setCardExpiration(null)
              ->setCardCvv(null);
        }

        return $order;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigPrefix()
    {
        return 'commerce.payment.transfirst';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredConfigurationFields()
    {
        
        $i = 0;
        return array_merge(parent::getRequiredConfigurationFields(),array(
             'merchant_id' => array(
                 'type' => 'string',
                 'value' => isset($this->defaultConfigurationValues['merchant_id']) ? $this->defaultConfigurationValues['merchant_id'] : null,
                 'label' => 'Merchant ID',
                 'help' => '',
                 'group' => sprintf('Payment/%s', $this->getName()),
                 'position' => ++$i,
                 'required' => false,
              ),
              'reg_key' => array(
                  'type' => 'encrypted',
                  'value' => isset($this->defaultConfigurationValues['reg_key']) ? $this->defaultConfigurationValues['reg_key'] : null,
                  'label' => 'Reg Key',
                  'help' => '',
                  'group' => sprintf('Payment/%s', $this->getName()),
                  'position' => ++$i,
                  'required' => false,
                ),
                'test_mode' => array(
                    'type' => 'boolean',
                    'value' => isset($this->defaultConfigurationValues['test_mode']) ? $this->defaultConfigurationValues['test_mode'] : null,
                    'label' => 'Test Mode',
                    'help' => '',
                    'group' => sprintf('Payment/%s', $this->getName()),
                    'position' => ++$i,
                    'required' => false,
                ),
        ));
        
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'transfirst';
    }

    /**
     * {@inheritDoc}
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritDoc}
     */
    public function chargeOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function voidOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function refundOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function authorizeOrder(OrderInterface $order)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function savesCreditCard()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setEncryptionManager($manager)
    {
        $this->encryptionManager = $manager;

        return $this;
    }

    /**
     * {@inheritDoc}
     */

    public function getEncryptionManager()
    {
        return $this->encryptionManager;
    }
}