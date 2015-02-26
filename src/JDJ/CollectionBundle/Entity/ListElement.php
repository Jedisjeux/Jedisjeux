<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ListElement
 *
 * @ORM\Entity(repositoryClass="JDJ\CollectionBundle\Repository\ListElementRepository")
 * @ORM\Table(name="jdj_list_element")
 *
 */
class ListElement
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
     * @var \JDJ\CollectionBundle\Entity\Collection
     *
     * @ORM\ManyToOne(targetEntity="JDJ\CollectionBundle\Entity\Collection", inversedBy="listElements")
     */
    private $collection;

    /**
     * @var \JDJ\JeuBundle\Entity\Jeu
     *
     * @ORM\ManyToOne(targetEntity="JDJ\JeuBundle\Entity\Jeu", inversedBy="listElements")
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
