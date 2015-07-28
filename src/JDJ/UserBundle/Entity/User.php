<?php

namespace JDJ\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JDJ\CollectionBundle\Entity\UserGameAttribute;

/**
 * Class User
 * @package JDJ\UserBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\UserBundle\Repository\UserRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"username"}, separator="-")
     * @ORM\Column(type="string", length=128)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var \DateTime $deletedAt
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\PartieBundle\Entity\Partie", mappedBy="author")
     */
    private $asAuthorParties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\PartieBundle\Entity\Partie", mappedBy="users", cascade={"persist"})
     */
    private $parties;

    /**
     * @var Avatar
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\Avatar" , cascade={"persist"})
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CollectionBundle\Entity\UserGameAttribute", mappedBy="user")
     */
    private $userGameAttributes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CollectionBundle\Entity\Collection", mappedBy="user")
     */
    private $collections;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="JDJ\JediZoneBundle\Entity\Notification", mappedBy="user" , cascade={"persist"})
     */
    private $notifications;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="JDJ\JediZoneBundle\Entity\Activity", mappedBy="users", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="jdj_user_activity")
     */
    private $activities;


    public function __construct()
    {
        parent::__construct();

        $this->asAuthorParties = new ArrayCollection();
        $this->parties = new ArrayCollection();
        $this->userGameAttributes = new ArrayCollection();
        $this->collections = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    /**
     * Add asAuthorParties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $asAuthorParties
     * @return User
     */
    public function addAsAuthorParty(\JDJ\PartieBundle\Entity\Partie $asAuthorParties)
    {
        $this->asAuthorParties[] = $asAuthorParties;

        return $this;
    }

    /**
     * Remove asAuthorParties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $asAuthorParties
     */
    public function removeAsAuthorParty(\JDJ\PartieBundle\Entity\Partie $asAuthorParties)
    {
        $this->asAuthorParties->removeElement($asAuthorParties);
    }

    /**
     * Get asAuthorParties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAsAuthorParties()
    {
        return $this->asAuthorParties;
    }

    /**
     * Add parties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $parties
     * @return User
     */
    public function addParty(\JDJ\PartieBundle\Entity\Partie $parties)
    {
        $this->parties[] = $parties;

        return $this;
    }

    /**
     * Remove parties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $parties
     */
    public function removeParty(\JDJ\PartieBundle\Entity\Partie $parties)
    {
        $this->parties->removeElement($parties);
    }

    /**
     * Get parties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParties()
    {
        return $this->parties;
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
     * Set slug
     *
     * @param string $slug
     * @return User
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }


    /**
     * Set presentation
     *
     * @param string $presentation
     * @return User
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string 
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @return Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param Avatar $avatar
     * @return $this
     */
    public function setAvatar(Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Add UserGameAttributes
     *
     * @param UserGameAttribute $userGameAttributes
     * @return $this
     */
    public function addUserGameAttribute(UserGameAttribute $userGameAttributes)
    {
        $this->userGameAttributes[] = $userGameAttributes;

        return $this;
    }

    /**
     * Remove UserGameAttributes
     *
     * @param UserGameAttribute $userGameAttributes
     */
    public function removeUserGameAttribute(UserGameAttribute $userGameAttributes)
    {
        $this->userGameAttributes->removeElement($userGameAttributes);
    }

    /**
     * Get UserGameAttributes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserGameAttributes()
    {
        return $this->userGameAttributes;
    }


    /**
     * Add Collections
     *
     * @param \JDJ\CollectionBundle\Entity\Collection $collections
     * @return User
     */
    public function addCollection(\JDJ\CollectionBundle\Entity\Collection $collections)
    {
        $this->collections[] = $collections;

        return $this;
    }

    /**
     * Remove Collections
     *
     * @param \JDJ\CollectionBundle\Entity\Collection $collections
     */
    public function removeCollection(\JDJ\CollectionBundle\Entity\Collection $collections)
    {
        $this->collections->removeElement($collections);
    }

    /**
     * Get Collections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCollections()
    {
        return $this->collections;
    }



    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }


    /**
     * Add notifications
     *
     * @param \JDJ\JediZoneBundle\Entity\Notification $notifications
     * @return User
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
     * Add activities
     *
     * @param \JDJ\JediZoneBundle\Entity\Activity $activities
     * @return User
     */
    public function addActivity(\JDJ\JediZoneBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \JDJ\JediZoneBundle\Entity\Activity $activities
     */
    public function removeActivity(\JDJ\JediZoneBundle\Entity\Activity $activities)
    {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }
}
