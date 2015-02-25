<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_produit")
 */
class Produit
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
    private $libelle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TarifProduit", mappedBy="produit", cascade={"persist", "merge"})
     */
    private $tarifs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="FactureProduit", mappedBy="produit", cascade={"persist", "merge"})
     */
    private $factureProduits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tarifs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set libelle
     *
     * @param string $libelle
     * @return Produit
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Add tarifs
     *
     * @param \JDJ\ComptaBundle\Entity\TarifProduit $tarifs
     * @return Produit
     */
    public function addTarif(\JDJ\ComptaBundle\Entity\TarifProduit $tarifs)
    {
        $this->tarifs[] = $tarifs;

        return $this;
    }

    /**
     * Remove tarifs
     *
     * @param \JDJ\ComptaBundle\Entity\TarifProduit $tarifs
     */
    public function removeTarif(\JDJ\ComptaBundle\Entity\TarifProduit $tarifs)
    {
        $this->tarifs->removeElement($tarifs);
    }

    /**
     * Get tarifs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTarifs()
    {
        return $this->tarifs;
    }


    /**
     * Add factureProduits
     *
     * @param \JDJ\ComptaBundle\Entity\FactureProduit $factureProduits
     * @return Produit
     */
    public function addFactureProduit(\JDJ\ComptaBundle\Entity\FactureProduit $factureProduits)
    {
        $this->factureProduits[] = $factureProduits;

        return $this;
    }

    /**
     * Remove factureProduits
     *
     * @param \JDJ\ComptaBundle\Entity\FactureProduit $factureProduits
     */
    public function removeFactureProduit(\JDJ\ComptaBundle\Entity\FactureProduit $factureProduits)
    {
        $this->factureProduits->removeElement($factureProduits);
    }

    /**
     * Get factureProduits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFactureProduits()
    {
        return $this->factureProduits;
    }
}
