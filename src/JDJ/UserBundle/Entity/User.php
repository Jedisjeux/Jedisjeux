<?php

namespace JDJ\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $prenom;

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
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $avatar;

    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public $avatarFile;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;


    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->asAuthorParties = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parties = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
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
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
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

    public function getAbsolutePath()
    {
        return null === $this->avatar ? null : $this->getUploadRootDir().'/'.$this->avatar;
    }

    /**
     * return the public url to the avatar
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->avatar ? null : $this->getUploadDir().'/'.$this->avatar;
    }

    /**
     * get the absolute path to the upload directory
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * The path to the  avatar files
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads/avatar';
    }

    /**
     * This function uploads the file to the server
     *
     */
    public function upload()
    {
        //Checks if the avatar is null
        if (null === $this->avatarFile) {
            return;
        }

        $this->avatarFile->move($this->getUploadRootDir(), $this->avatarFile->getClientOriginalName());
        $this->avatar = $this->avatarFile->getClientOriginalName();

        // Clean the avatar file
        $this->avatarFile = null;
    }

}
