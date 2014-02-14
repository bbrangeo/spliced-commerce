<?php

namespace Spliced\Bundle\CommerceAdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spliced\Component\Commerce\Model\Visitor as BaseVisitor;

/**
 * Visitor
 *
 * @ORM\Table(name="visitor")
 * @ORM\Entity(repositoryClass="Spliced\Bundle\CommerceAdminBundle\Repository\VisitorRepository")
 */
class Visitor extends BaseVisitor
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
     * @ORM\Column(name="session_id", type="string", length=64)
     */
    protected $sessionId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255)
     */
    protected $userAgent;

    /**
     * @var $isBot
     *
     * @ORM\Column(name="is_bot", type="boolean")
     */
    protected $isBot;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20)
     */
    protected $ip;
    
    /**
     * @var string
     *
     * @ORM\Column(name="host_name", type="string", length=255)
     */
    protected $hostName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="initial_referer", type="string", length=50)
     */
    protected $initialReferer;

    /**
     * @var string
     *
     * @ORM\Column(name="initial_referer_url", type="string", length=255)
     */
    protected $initialRefererUrl;

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
     * @ORM\OneToMany(targetEntity="VisitorRequest", mappedBy="visitor", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="visitor_id")
     */
    protected $requests;

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
