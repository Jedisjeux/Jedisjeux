<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListElement
 */
class ListElement
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \JDJ\CollectionBundle\Entity\Collection
     */
    private $collection;

    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     */
    private $jeu;


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
     * Set collection
     *
     * @param \JDJ\CollectionBundle\Entity\Collection $collection
     * @return ListElement
     */
    public function setCollection(\JDJ\CollectionBundle\Entity\Collection $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return \JDJ\CollectionBundle\Entity\Collection 
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set jeu
     *
     * @param \JDJ\JeuBundle\Entity\Jeu $jeu
     * @return ListElement
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
}
