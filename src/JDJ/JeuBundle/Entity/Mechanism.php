<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Mechanism
 *
 * @ORM\Entity(repositoryClass="JDJ\JeuBundle\Repository\MechanismRepository")
 * @ORM\Table(name="jdj_mechanism")
 */
class Mechanism implements ResourceInterface
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
     * @ORM\Column(type="string", nullable=true, length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, separator="-")
     * @ORM\Column(type="string", length=128)
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Jeu", mappedBy="mechanisms", cascade={"persist", "merge"})
     */
    private $jeux;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $aliases;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jeux = new ArrayCollection();
        $this->aliases = array();
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
     * @return Mechanism
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
     * @return Mechanism
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
     * @return Mechanism
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
     * @return Mechanism
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
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @param array $aliases
     *
     * @return $this
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;

        return $this;
    }

    /**
     * Entity To String
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
