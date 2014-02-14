<?php

namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\VisitorRequest as BaseVisitorRequest;

/**
 * VisitorRequest
 *
 * @ORM\Table(name="visitor_request")
 * @ORM\Entity
 */
class VisitorRequest extends BaseVisitorRequest
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="requested_path", type="string", length=255)
     */
    protected $requestedPath;

    /**
     * @var string
     *
     * @ORM\Column(name="referer", type="string", length=255)
     */
    protected $referer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var Visitor
     *
     * @ORM\ManyToOne(targetEntity="Visitor", inversedBy="requests")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     * })
     */
    protected $visitor;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
