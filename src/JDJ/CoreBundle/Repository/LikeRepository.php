<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 26/03/15
 * Time: 19:29
 */

namespace JDJ\CoreBundle\Repository;
use JDJ\CoreBundle\Entity\EntityRepository;
use JDJ\CoreBundle\Entity\Like;
use JDJ\UserBundle\Entity\User;

/**
 * Class LikeRepository
 * @package JDJ\CoreBundle\Repository
 */
class LikeRepository extends EntityRepository
{
    /**
     * Find like entity of a user on any type of linked entity (userReview etc...)
     *
     * @param string $entityName
     * @param $entity
     * @return Like
     */
    public function findUserLikeOnEntity(User $user, $entityName, $entity)
    {
        $queryBuilder = $this->createQueryBuilder($this->getAlias());

        $queryBuilder
            ->andWhere($this->getAlias().'.createdBy = :user')
            ->setParameter('user', $user)
            ->andWhere($this->getAlias().'.'.$entityName . ' = :entity')
            ->setParameter('entity', $entity);

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();
    }
} 