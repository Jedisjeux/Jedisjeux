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

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostRepository extends EntityRepository
{
    /**
     * @param integer|null $topicId
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder($topicId = null)
    {
        $queryBuilder = $this
            ->createQueryBuilder('o')
            ->join('o.topic', 'topic');

        if (null !== $topicId) {
            $queryBuilder
                ->andWhere('topic = :topic')
                ->setParameter('topic', $topicId);
        }

        return $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this
            ->createQueryBuilder('o')
            ->addSelect('user')
            ->addSelect('customer')
            ->addSelect('avatar')
            ->addSelect('topic')
            ->join('o.author', 'customer')
            ->join('customer.user', 'user')
            ->join('o.topic', 'topic')// assume mainPost is excluded
            ->leftJoin('customer.avatar', 'avatar');
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = array(), $sorting = array())
    {
        $queryBuilder = $this->getQueryBuilder();

        if (!empty($criteria['query'])) {

            $queryBuilder
                ->where($queryBuilder->expr()->orX(
                    'user.username like :query',
                    'topic.title like :query'
                ))
                ->setParameter('query', '%' . $criteria['query'] . '%');

            unset($criteria['query']);
        }

        if (isset($criteria['product'])) {

            $queryBuilder
                ->where($queryBuilder->expr()->orX(
                    'article.product = :product',
                    'gamePlay.product = :product'
                ))
                ->setParameter('product', $criteria['product']);

            unset($criteria['product']);
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['createdAt'] = 'desc';
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }
}
