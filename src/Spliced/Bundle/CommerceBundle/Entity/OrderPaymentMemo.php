<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Spliced\Component\Commerce\Model\OrderPaymentMemo as BaseOrderPaymentMemo;

/**
 * OrderPaymentMemo
 *
 * @ORM\Table(name="customer_order_payment_memo")
 * @ORM\Entity()
 * @ORM\MappedSuperclass()
 */
class OrderPaymentMemo extends BaseOrderPaymentMemo
{
    

    


}
