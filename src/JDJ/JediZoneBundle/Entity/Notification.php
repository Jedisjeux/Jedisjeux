<?php

namespace JDJ\JediZoneBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Notification
 * @package JDJ\JediZoneBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\JediZoneBundle\Repository\NotificationRepository")
 * @ORM\Table(name="jdj_notification")
 *
 */
class Notification
{
    const ACTION_ACCEPT = 'accept';
    const ACTION_DECLINE = 'decline';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var $action
     *
     * @ORM\Column(name="status", type="string", columnDefinition="enum('accept', 'decline')")
     */
    private $action;


    /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $isRead;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $changeStatus;

    /**
     * @var boolean
     */
    private $displayNew;

    /**
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User", inversedBy="notifications")
     */
    private $user;

    /**
     * @var Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="notifications")
     */
    private $activity;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listElements = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Notification
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Notification
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return Notification
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set read
     *
     * @param boolean $isRead
     * @return Notification
     */
    public function setRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean 
     */
    public function isRead()
    {
        return $this->isRead;
    }

    /**
     * Set user
     *
     * @param \JDJ\UserBundle\Entity\User $user
     * @return Notification
     */
    public function setUser(\JDJ\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \JDJ\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set activity
     *
     * @param \JDJ\JediZoneBundle\Entity\Activity $activity
     * @return Notification
     */
    public function setActivity(\JDJ\JediZoneBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \JDJ\JediZoneBundle\Entity\Activity 
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set changeStatus
     *
     * @param string $changeStatus
     *
     * @return Notification
     */
    public function setChangeStatus($changeStatus)
    {
        $this->changeStatus = $changeStatus;

        return $this;
    }

    /**
     * Get changeStatus
     *
     * @return string
     */
    public function getChangeStatus()
    {
        return $this->changeStatus;
    }

    /**
     * @return boolean
     */
    public function isDisplayNew()
    {
        return $this->displayNew;
    }

    /**
     * @param boolean $displayNew
     */
    public function setDisplayNew($displayNew)
    {
        $this->displayNew = $displayNew;
    }
}
