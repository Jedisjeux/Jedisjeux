<?php

namespace JDJ\CollectionBundle\Manager;

use Doctrine\Common\Util\Debug;
use Doctrine\ORM\EntityManager;
use JDJ\CollectionBundle\Entity\UserGameAttribute;

class UserGameAttributeManager
{

    private $em;

    /**
     * Constructor
     *
     * @param EntityManager $em
     */
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * This function insert new UserGameAttribute
     *
     * @param UserGameAttribute $userGameAttribute
     */
    public function record(UserGameAttribute $userGameAttribute)
    {
        $this->em->persist($userGameAttribute);
        $this->em->flush();
    }

}
