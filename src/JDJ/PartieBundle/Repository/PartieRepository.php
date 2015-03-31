<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 27/03/15
 * Time: 00:06
 */

namespace JDJ\PartieBundle\Repository;


use JDJ\CoreBundle\Entity\EntityRepository;
use JDJ\UserBundle\Entity\User;

/**
 * Class PartieRepository
 * @package JDJ\PartieBundle\Repository
 */
class PartieRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return \Pagerfanta\Pagerfanta
     */
    public function findByUser(User $user)
    {
        $queryBuilder = $this->createQueryBuilder($this->getAlias());

        $queryBuilder
            ->join($this->getAlias().'.users', 'user')
            ->andWhere('user = :user')
            ->setParameter('user', $user)
            ->orderBy($this->getAlias().'.createdAt', 'desc');

        return $this->getPaginator($queryBuilder);

    }
} 