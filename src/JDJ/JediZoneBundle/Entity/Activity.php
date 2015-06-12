<?php

namespace JDJ\JediZoneBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Activity
 * @package JDJ\JediZoneBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\JediZoneBundle\Repository\ActivityRepository")
 * @ORM\Table(name="jdj_activity")
 *
 */
class Activity
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User", cascade={"persist"})
     */
    private $actionUser;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\UserBundle\Entity\User", inversedBy="activities", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_user_activity")
     */
    private $users;

    /**
     * @var JDJ\JeuBundle\Entity\Jeu
     *
     * @ORM\OneToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu", inversedBy="activity", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="jeu_id", referencedColumnName="id")
     */
    private $jeu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="activity" , cascade={"persist"})
     */
    private $notifications;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }





    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set published
     *
     * @param \DateTime $published
     * @return Activity
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return \DateTime 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Add users
     *
     * @param \JDJ\UserBundle\Entity\User $users
     * @return Activity
     */
    public function addUser(\JDJ\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \JDJ\UserBundle\Entity\User $users
     */
    public function removeUser(\JDJ\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add notifications
     *
     * @param \JDJ\JediZoneBundle\Entity\Notification $notifications
     * @return Activity
     */
    public function addNotification(\JDJ\JediZoneBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \JDJ\JediZoneBundle\Entity\Notification $notifications
     */
    public function removeNotification(\JDJ\JediZoneBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     *
     * @return Activity
     */
    public function setJeu(\JDJ\JeuBundle\Entity\Jeu $jeu = null)
    {
        $this->jeu = $jeu;

        return $this;
    }

    /**
     * Get jeu
     *
     * @return \JDJ\JeuBundle\Entity\Jeu
     */
    public function getJeu()
    {
        return $this->jeu;
    }

    /**
     * Set actionUser
     *
     * @param \JDJ\UserBundle\Entity\User $actionUser
     *
     * @return Activity
     */
    public function setActionUser(\JDJ\UserBundle\Entity\User $actionUser = null)
    {
        $this->actionUser = $actionUser;

        return $this;
    }

    /**
     * Get actionUser
     *
     * @return \JDJ\UserBundle\Entity\User
     */
    public function getActionUser()
    {
        return $this->actionUser;
    }
}
