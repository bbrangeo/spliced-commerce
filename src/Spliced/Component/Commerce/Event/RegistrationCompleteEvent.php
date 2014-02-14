<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Event;

use Spliced\Component\Commerce\Model\CustomerInterface;

/**
 * RegistrationCompleteEvent
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class RegistrationCompleteEvent extends Event
{

    /**
     * @param CustomerInterface $customer
     */
    public function __construct(CustomerInterface $customer, $requireConfirmation = false)
    {
        $this->customer = $customer;
        $this->requireConfirmation = $requireConfirmation === true ? true : false;
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * @return bool
     */
    public function requiresConfirmation()
    {
        return $this->requireConfirmation;
    }
    
}
