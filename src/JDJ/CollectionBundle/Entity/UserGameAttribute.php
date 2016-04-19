<?php

namespace JDJ\CollectionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserGameAttribute
 * @package JDJ\CollectionBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\CollectionBundle\Repository\UserGameAttributeRepository")
 * @ORM\Table(name="jdj_user_game_attribute")
 *
 */
class UserGameAttribute
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
