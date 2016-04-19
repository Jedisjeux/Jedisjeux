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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
