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

use Spliced\Component\Commerce\Model\ContactMessage as BaseContactMessage;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContactMessage
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * @ORM\Table(name="contact_message")
 * @ORM\Entity()
 */
class ContactMessage extends BaseContactMessage
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
     * @ORM\OneToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;
    
    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=150, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=false)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = false
     * )
     * @Assert\NotBlank()
     */
    protected $email;
    
    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=150, nullable=false)
     */
    protected $phone;
    
    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="string", length=150, nullable=false)
     * @Assert\NotBlank()
     */
    protected $subject;
    

    /**
     * @var string $comment
     *
     * @ORM\Column(name="comment", type="text", length=150, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=50,
     *     minMessage="Your comment must have at least {{ limit }} characters."
     * )
     */
    protected $comment;
    
    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;
    
}