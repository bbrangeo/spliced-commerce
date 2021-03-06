<?php
/*
 * This file is part of the SplicedCommerceAdminBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Spliced\Component\Commerce\Model\OrderShipmentMemo as BaseOrderShipmentMemo;

/**
 * OrderShipmentMemo
 *
 * @ORM\Table(name="customer_order_shipment_memo")
 * @ORM\Entity()
 * @ORM\MappedSuperclass()
 */
class OrderShipmentMemo extends BaseOrderShipmentMemo
{
 
}