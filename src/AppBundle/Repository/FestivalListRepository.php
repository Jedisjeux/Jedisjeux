<?php

/*
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListRepository extends EntityRepository
{
    /**
     * @param int $count
     *
     * @return array
     */
    public function findLatest($count)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->gte(':today', 'o.startAt'))
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->lt(':today', 'o.endAt'),
                $queryBuilder->expr()->isNull('o.endAt')
            ))
            ->addOrderBy('o.createdAt', 'DESC')
            ->setMaxResults($count)
            ->setParameter('today', (new \DateTime('today'))->format('Y-m-d'));

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
