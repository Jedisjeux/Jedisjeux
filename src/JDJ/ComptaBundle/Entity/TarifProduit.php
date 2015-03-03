<?php

namespace JDJ\ComptaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TarifProduit
 *
 * @ORM\Entity
 * @ORM\Table(name="cpta_tarif_produit")
 */
class TarifProduit
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateFin;

    /**
     * @var decimal
     *
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=false)
     */
    private $prix;

    /**
     * @var \JDJ\ComptaBundle\Entity\Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="tarifs", cascade={"persist", "merge"})
     */
    private $produit;


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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return TarifProduit
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return TarifProduit
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set prix
     *
     * @param string $prix
     * @return TarifProduit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set produit
     *
     * @param \JDJ\ComptaBundle\Entity\Produit $produit
     * @return TarifProduit
     */
    public function setProduit(\JDJ\ComptaBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \JDJ\ComptaBundle\Entity\Produit 
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
