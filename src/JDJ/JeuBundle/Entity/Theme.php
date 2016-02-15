<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class Theme
 * @package JDJ\JeuBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\JeuBundle\Repository\ThemeRepository")
 * @ORM\Table(name="jdj_theme")
 */
class Theme implements ResourceInterface
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"name"}, separator="-")
     * @ORM\Column(type="string", length=128)
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Jeu", mappedBy="themes")
     */
    private $jeux;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeux = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $name
     * @return Theme
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add jeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeux
     * @return Theme
     */
    public function addJeux(\JDJ\JeuBundle\Entity\Jeu $jeux)
    {
        $this->jeux[] = $jeux;

        return $this;
    }

    /**
     * Remove jeux
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeux
     */
    public function removeJeux(\JDJ\JeuBundle\Entity\Jeu $jeux)
    {
        $this->jeux->removeElement($jeux);
    }

    /**
     * Get jeux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJeux()
    {
        return $this->jeux;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Theme
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
     * @return Theme
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
     * Convert entity to String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
