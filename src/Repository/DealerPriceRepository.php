<?php

/*
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Dealer;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerPriceRepository extends EntityRepository
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->leftjoin('o.product', 'product')
            ->leftJoin('product.translations', 'translation', Join::WITH, 'translation.locale = :localeCode')
            ->leftJoin('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->setParameter('localeCode', $localeCode)
            ->setParameter('main', true);

        return $queryBuilder;
    }

    /**
     * @param Dealer $dealer
     */
    public function deleteByDealer(Dealer $dealer): void
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->delete()
            ->where('o.dealer = :dealer')
            ->setParameter('dealer', $dealer);

        $queryBuilder->getQuery()->execute();
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        if (isset($criteria['hasProduct'])) {
            if ($criteria['hasProduct']) {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->isNotNull($this->getPropertyName('product')));
            } else {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->isNull($this->getPropertyName('product')));
            }
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * {@inheritdoc}
     */
    protected function getQueryBuilder()
    {
        return parent::createQueryBuilder('o')
            ->addSelect('dealer, product, productTranslation')
            ->innerJoin($this->getPropertyName('dealer'), 'dealer')
            ->leftJoin($this->getPropertyName('product'), 'product')
            ->leftJoin($this->getPropertyName('product.translations'), 'productTranslation');
    }

    /**
     * {@inheritdoc}
     */
    public function createFilterPaginator(array $criteria = null, array $sorting = null)
    {
        $queryBuilder = $this->getQueryBuilder();

        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->orX(
                    $this->getPropertyName('name like :query'),
                    $this->getPropertyName('dealer.name like :query')
                ))
                ->setParameter('query', '%'.$criteria['query'].'%');

            unset($criteria['query']);
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['createdAt'] = 'desc';
            $sorting['id'] = 'desc';
        }

        $this->applyCriteria($queryBuilder, (array) $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }
}
