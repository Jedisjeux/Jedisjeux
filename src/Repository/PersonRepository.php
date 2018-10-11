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

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonRepository extends EntityRepository
{
    /**
     * @param TaxonInterface|null $taxon
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(TaxonInterface $taxon = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('image')
            ->leftJoin('o.images', 'image', Join::WITH, 'image.main = 1');


        if ($taxon) {
            $queryBuilder
                ->innerJoin('o.taxons', 'taxon')
                ->andWhere($queryBuilder->expr()->orX(
                    'taxon = :taxon',
                    ':left < taxon.left AND taxon.right < :right AND taxon.root = :root'
                ))
                ->setParameter('taxon', $taxon)
                ->setParameter('left', $taxon->getLeft())
                ->setParameter('right', $taxon->getRight())
                ->setParameter('root', $taxon->getRoot());
        }

        return $queryBuilder;
    }

    /**
     * @param array $criteria
     * @param array $sorting
     * @param TaxonInterface|null $taxon
     *
     * @return QueryBuilder
     */
    public function createFrontendListQueryBuilder(array $criteria = [], array $sorting = [], TaxonInterface $taxon = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('image')
            ->leftJoin('o.images', 'image', Join::WITH, 'image.main = 1');


        // for taxon grid filter
        if (isset($criteria['zone']) && !empty($criteria['zone']['mainTaxon'])) {
            $queryBuilder
                ->innerJoin('o.taxons', 'taxon');
        }

        $this->sortingOnProductCountIfNecessary($queryBuilder, $criteria, $sorting);

        if ($taxon) {
            $queryBuilder
                ->innerJoin('o.taxons', 'taxon')
                ->andWhere($queryBuilder->expr()->orX(
                    'taxon = :taxon',
                    ':left < taxon.left AND taxon.right < :right AND taxon.root = :root'
                ))
                ->setParameter('taxon', $taxon)
                ->setParameter('left', $taxon->getLeft())
                ->setParameter('right', $taxon->getRight())
                ->setParameter('root', $taxon->getRoot());
        }

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function findByNamePart($phrase)
    {
        $queryBuilder =  $this->createQueryBuilder('o');

        return $queryBuilder
            ->orWhere(
                "concat(o.firstName, ' ', o.lastName) LIKE :name",
                'o.lastName like :name'
            )
            ->setParameter('name', '%'.$phrase.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array()): void
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere('o.slug like :query')
                ->setParameter('query', '%' . $criteria['query'] . '%');

            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [])
    {
        $queryBuilder = $this->createQueryBuilder('o');

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['id'] = 'asc';
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Create paginator for products categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = [], array $sorting = [])
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->innerJoin('o.taxons', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right AND taxon.root = :root'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight())
            ->setParameter('root', $taxon->getRoot());

        $this->applyCriteria($queryBuilder, $criteria);
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
            ->select($queryBuilder->expr()->count('o'));
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     * @param array $sorting
     */
    private function sortingOnProductCountIfNecessary(QueryBuilder $queryBuilder, array $criteria, array $sorting)
    {
        $role = $criteria['role'] ?? null;
        $sortingByProductCount = 0 === count($sorting) || isset($sorting['productCount']);

        if (null !== $role && $sortingByProductCount) {
            $order = $sorting['productCount'] ?? 'desc';
            $sort = $this->getProductCountPropertyNameByRole($role);

            // cannot apply another product count property to apply sorting
            if (null === $sort) {
                return;
            }

            $queryBuilder->orderBy($sort, $order);
            unset($sorting['productCount']);
        }
    }

    /**
     * @param string $role
     *
     * @return null|string
     */
    private function getProductCountPropertyNameByRole(string $role): ?string
    {
        switch ($role) {
            case 'designers':
                return 'o.productCountAsDesigner';
            case 'artists':
                return 'o.productCountAsArtist';
            case 'publishers':
                return 'o.productCountAsPublisher';
        }

        return null;
    }
}