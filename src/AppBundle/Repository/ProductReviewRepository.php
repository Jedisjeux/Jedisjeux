<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 17/03/2016
 * Time: 00:28
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Review\Model\ReviewInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ProductReviewRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->select('o, product, productVariant, productTranslation')
            ->join('o.reviewSubject', 'product')
            ->leftJoin('product.variants', 'productVariant')
            ->leftJoin('product.translations', 'productTranslation');
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
    {
        if (isset($criteria['hasComment']) and $criteria['hasComment'] !== '') {
            if ($criteria['hasComment']) {
                $queryBuilder
                    ->andWhere('o.comment is not null');
            } else {
                // Sylius entity repository handles this case
                $criteria['comment'] = null;
            }
            unset($criteria['hasComment']);
        }


        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [])
    {
        $queryBuilder = $this->getQueryBuilder()
            ->groupBy('o.id');

        if (isset($criteria['person'])) {
            $queryBuilder
                ->leftJoin('productVariant.designers', 'designer')
                ->leftJoin('productVariant.artists', 'artist')
                ->leftJoin('productVariant.publishers', 'publisher')
                ->andWhere($queryBuilder->expr()->orX(
                    'designer = :person',
                    'artist = :person',
                    'publisher = :person'
                ))
                ->setParameter('person', $criteria['person']);

            unset($criteria['person']);
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['updatedAt'] = 'desc';
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNbResults()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select($queryBuilder->expr()->count('o'))
            ->where($queryBuilder->expr()->eq($this->getPropertyName('status'), ':status'))
            ->setParameter('status', ReviewInterface::STATUS_ACCEPTED);
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

}