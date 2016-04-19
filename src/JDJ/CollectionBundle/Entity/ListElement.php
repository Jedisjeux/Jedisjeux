<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ListElement
 * @package JDJ\CollectionBundle\Entity
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
