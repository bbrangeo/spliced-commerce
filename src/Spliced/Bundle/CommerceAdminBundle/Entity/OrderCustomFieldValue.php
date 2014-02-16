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
use Spliced\Component\Commerce\Model\OrderCustomFieldValue as BaseOrderCustomFieldValue;

/**
 * Spliced\Bundle\CommerceAdminBundle\Entity\OrderCustomFieldValue
 *
 * @ORM\Table(name="customer_order_custom_field_value")
 * @ORM\Entity()
 */
class OrderCustomFieldValue extends BaseOrderCustomFieldValue
{
    /**
     * @var bigint $id
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string $fieldValue
     *
     * @ORM\Column(name="field_value", type="string", nullable=false)
     */
    protected $fieldValue;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="customFields")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $order;
    
    /**
     * getId
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
}