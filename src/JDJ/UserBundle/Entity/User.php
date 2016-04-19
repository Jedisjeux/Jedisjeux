<?php

namespace JDJ\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JDJ\CollectionBundle\Entity\UserGameAttribute;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Class User
 * @package JDJ\UserBundle\Entity
 *
 * @ORM\Entity(repositoryClass="JDJ\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements ResourceInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
