<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Jeu
 */
class Jeu
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var integer
     */
    private $ageMin;

    /**
     * @var integer
     */
    private $joueurMin;

    /**
     * @var integer
     */
    private $joueurMax;

    /**
     * @var string
     */
    private $intro;

    /**
     * @var string
     */
    private $materiel;

    /**
     * @var string
     */
    private $but;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $imageCouverture;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $jeuCaracteristiques;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $addons;

    /**
     * @var \JDJ\WebBundle\Entity\Statut
     */
    private $statut;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $mecanismes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $themes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $auteurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $illustrateurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $editeurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $parties;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cibles;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $notes;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeuCaracteristiques = new ArrayCollection();
        $this->addons = new ArrayCollection();
        $this->mecanismes = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->auteurs = new ArrayCollection();
        $this->illustrateurs = new ArrayCollection();
        $this->editeurs = new ArrayCollection();
        $this->parties = new ArrayCollection();
        $this->cibles = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    /**
     * Convert Entity To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
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
     * @return Jeu
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
     * Set ageMin
     *
     * @param integer $ageMin
     * @return Jeu
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    /**
     * Get ageMin
     *
     * @return integer 
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }

    /**
     * Set joueurMin
     *
     * @param integer $joueurMin
     * @return Jeu
     */
    public function setJoueurMin($joueurMin)
    {
        $this->joueurMin = $joueurMin;

        return $this;
    }

    /**
     * Get joueurMin
     *
     * @return integer 
     */
    public function getJoueurMin()
    {
        return $this->joueurMin;
    }

    /**
     * Set joueurMax
     *
     * @param integer $joueurMax
     * @return Jeu
     */
    public function setJoueurMax($joueurMax)
    {
        $this->joueurMax = $joueurMax;

        return $this;
    }

    /**
     * Get joueurMax
     *
     * @return integer 
     */
    public function getJoueurMax()
    {
        return $this->joueurMax;
    }

    /**
     * Set intro
     *
     * @param string $intro
     * @return Jeu
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string 
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set materiel
     *
     * @param string $materiel
     * @return Jeu
     */
    public function setMateriel($materiel)
    {
        $this->materiel = $materiel;

        return $this;
    }

    /**
     * Get materiel
     *
     * @return string 
     */
    public function getMateriel()
    {
        return $this->materiel;
    }

    /**
     * Set but
     *
     * @param string $but
     * @return Jeu
     */
    public function setBut($but)
    {
        $this->but = $but;

        return $this;
    }

    /**
     * Get but
     *
     * @return string 
     */
    public function getBut()
    {
        return $this->but;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Jeu
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Jeu
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
     * Set imageCouverture
     *
     * @param string $imageCouverture
     * @return Jeu
     */
    public function setImageCouverture($imageCouverture)
    {
        $this->imageCouverture = $imageCouverture;

        return $this;
    }

    /**
     * Get imageCouverture
     *
     * @return string 
     */
    public function getImageCouverture()
    {
        return $this->imageCouverture;
    }

    /**
     * Add jeuCaracteristiques
     *
     * @param \JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques
     * @return Jeu
     */
    public function addJeuCaracteristique(\JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques)
    {
        $this->jeuCaracteristiques[] = $jeuCaracteristiques;

        return $this;
    }

    /**
     * Remove jeuCaracteristiques
     *
     * @param \JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques
     */
    public function removeJeuCaracteristique(\JDJ\JeuBundle\Entity\JeuCaracteristique $jeuCaracteristiques)
    {
        $this->jeuCaracteristiques->removeElement($jeuCaracteristiques);
    }

    /**
     * Get jeuCaracteristiques
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeuCaracteristiques()
    {
        return $this->jeuCaracteristiques;
    }

    /**
     * Add addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     * @return Jeu
     */
    public function addAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->addons[] = $addons;

        return $this;
    }

    /**
     * Remove addons
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addons
     */
    public function removeAddon(\JDJ\JeuBundle\Entity\Addon $addons)
    {
        $this->addons->removeElement($addons);
    }

    /**
     * Get addons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddons()
    {
        return $this->addons;
    }

    /**
     * Set statut
     *
     * @param \JDJ\WebBundle\Entity\Statut $statut
     * @return Jeu
     */
    public function setStatut(\JDJ\WebBundle\Entity\Statut $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \JDJ\WebBundle\Entity\Statut 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add mecanismes
     *
     * @param \JDJ\JeuBundle\Entity\Mecanisme $mecanismes
     * @return Jeu
     */
    public function addMecanisme(\JDJ\JeuBundle\Entity\Mecanisme $mecanismes)
    {
        $this->mecanismes[] = $mecanismes;

        return $this;
    }

    /**
     * Remove mecanismes
     *
     * @param \JDJ\JeuBundle\Entity\Mecanisme $mecanismes
     */
    public function removeMecanisme(\JDJ\JeuBundle\Entity\Mecanisme $mecanismes)
    {
        $this->mecanismes->removeElement($mecanismes);
    }

    /**
     * Get mecanismes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMecanismes()
    {
        return $this->mecanismes;
    }

    /**
     * Add themes
     *
     * @param \JDJ\JeuBundle\Entity\Theme $themes
     * @return Jeu
     */
    public function addTheme(\JDJ\JeuBundle\Entity\Theme $themes)
    {
        $this->themes[] = $themes;

        return $this;
    }

    /**
     * Remove themes
     *
     * @param \JDJ\JeuBundle\Entity\Theme $themes
     */
    public function removeTheme(\JDJ\JeuBundle\Entity\Theme $themes)
    {
        $this->themes->removeElement($themes);
    }

    /**
     * Get themes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Add auteurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $auteurs
     * @return Jeu
     */
    public function addAuteur(\JDJ\LudographieBundle\Entity\Personne $auteurs)
    {
        $this->auteurs[] = $auteurs;

        return $this;
    }

    /**
     * Remove auteurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $auteurs
     */
    public function removeAuteur(\JDJ\LudographieBundle\Entity\Personne $auteurs)
    {
        $this->auteurs->removeElement($auteurs);
    }

    /**
     * Get auteurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAuteurs()
    {
        return $this->auteurs;
    }

    /**
     * Add illustrateurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $illustrateurs
     * @return Jeu
     */
    public function addIllustrateur(\JDJ\LudographieBundle\Entity\Personne $illustrateurs)
    {
        $this->illustrateurs[] = $illustrateurs;

        return $this;
    }

    /**
     * Remove illustrateurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $illustrateurs
     */
    public function removeIllustrateur(\JDJ\LudographieBundle\Entity\Personne $illustrateurs)
    {
        $this->illustrateurs->removeElement($illustrateurs);
    }

    /**
     * Get illustrateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIllustrateurs()
    {
        return $this->illustrateurs;
    }

    /**
     * Add editeurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $editeurs
     * @return Jeu
     */
    public function addEditeur(\JDJ\LudographieBundle\Entity\Personne $editeurs)
    {
        $this->editeurs[] = $editeurs;

        return $this;
    }

    /**
     * Remove editeurs
     *
     * @param \JDJ\LudographieBundle\Entity\Personne $editeurs
     */
    public function removeEditeur(\JDJ\LudographieBundle\Entity\Personne $editeurs)
    {
        $this->editeurs->removeElement($editeurs);
    }

    /**
     * Get editeurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEditeurs()
    {
        return $this->editeurs;
    }

    /**
     * Add parties
     *
     * @param \JDJ\PartieBundle\Entity\Partie $parties
     * @return Jeu
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
     * Add cibles
     *
     * @param \JDJ\CoreBundle\Entity\Cible $cibles
     * @return Jeu
     */
    public function addCible(\JDJ\CoreBundle\Entity\Cible $cibles)
    {
        $this->cibles[] = $cibles;

        return $this;
    }

    /**
     * Remove cibles
     *
     * @param \JDJ\CoreBundle\Entity\Cible $cibles
     */
    public function removeCible(\JDJ\CoreBundle\Entity\Cible $cibles)
    {
        $this->cibles->removeElement($cibles);
    }

    /**
     * Get cibles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCibles()
    {
        return $this->cibles;
    }

    /**
     * Add notes
     *
     * @param \JDJ\UserReviewBundle\Entity\JeuNote $notes
     * @return Jeu
     */
    public function addNote(\JDJ\UserReviewBundle\Entity\JeuNote $notes)
    {
        $this->notes[] = $notes;

        return $this;
    }

    /**
     * Remove notes
     *
     * @param \JDJ\UserReviewBundle\Entity\JeuNote $notes
     */
    public function removeNote(\JDJ\UserReviewBundle\Entity\JeuNote $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
