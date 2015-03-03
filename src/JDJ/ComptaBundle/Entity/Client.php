<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Client
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_client")
 */
class Client
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $societe;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $rue;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $complement;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $ville;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var factures
     *
     * @ORM\OneToMany(targetEntity="Facture", mappedBy="client", cascade={"persist", "merge"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $factures;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set societe
     *
     * @param string $societe
     * @return Client
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe
     *
     * @return string 
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Client
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
     * Set email
     *
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set rue
     *
     * @param string $rue
     * @return Client
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string 
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set complement
     *
     * @param string $complement
     * @return Client
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string 
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     * @return Client
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Client
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Add factures
     *
     * @param \JDJ\ComptaBundle\Entity\Facture $factures
     * @return Client
     */
    public function addFacture(\JDJ\ComptaBundle\Entity\Facture $factures)
    {
        $this->factures[] = $factures;

        return $this;
    }

    /**
     * Remove factures
     *
     * @param \JDJ\ComptaBundle\Entity\Facture $factures
     */
    public function removeFacture(\JDJ\ComptaBundle\Entity\Facture $factures)
    {
        $this->factures->removeElement($factures);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFactures()
    {
        return $this->factures;
    }
}
