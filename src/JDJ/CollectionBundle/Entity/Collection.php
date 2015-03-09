<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Collection
 * @package JDJ\CollectionBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\CollectionBundle\Repository\CollectionRepository")
 * @ORM\Table(name="jdj_collection")
 *
 */
class Collection
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
    private $name;

    /**
     * @var text
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
     * @var \JDJ\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="JDJ\UserBundle\Entity\User", inversedBy="collections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ListElement", mappedBy="collection" , cascade={"persist"})
     */
    private $listElements;

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
     * Set name
     *
     * @param string $name
     * @return Collection
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Collection
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
     * @return Collection
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
     * Set user
     *
     * @param \JDJ\UserBundle\Entity\User $user
     * @return Collection
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
     * Add listElements
     *
     * @param ListElement $listElement
     * @return $this
     */
    public function addListElement(ListElement $listElement)
    {
        $this->listElements[] = $listElement;

        return $this;
    }

    /**
     * Remove listElements
     *
     * @param ListElement $listElement
     */
    public function removeListElement(ListElement $listElement)
    {
        $this->listElements->removeElement($listElement);
    }

    /**
     * Get listElements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListElements()
    {
        return $this->listElements;
    }
}
