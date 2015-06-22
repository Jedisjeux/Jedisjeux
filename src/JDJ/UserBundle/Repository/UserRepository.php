<?php

namespace JDJ\UserBundle\Repository;

use JDJ\CoreBundle\Entity\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * This function gets all user with a certain role
     *
     * @param string $role
     *
     * @return array
     */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }
}