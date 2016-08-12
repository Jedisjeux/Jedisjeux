<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/03/2016
 * Time: 09:33
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use AppBundle\Utils\DateCalculator;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductRepository extends BaseProductRepository
{
    /**
     * {@inheritdoc}
     */
    protected function getQueryBuilder()
    {
        return parent::createQueryBuilder('o')
            ->select('o, option, variant, image, translation')
            ->leftJoin('o.translations', 'translation')
            ->leftJoin('o.options', 'option')
            ->leftJoin('o.variants', 'variant')
            ->leftJoin('variant.images', 'image');
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById($id)
    {
        return $this->getQueryBuilder()
            ->where('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
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
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->innerJoin('o.taxons', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                'o.mainTaxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     * @param bool $deleted
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [], $deleted = false)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->andWhere('variant.master = :master')
            ->setParameter('master', 1);

        if (!empty($criteria['name'])) {
            $queryBuilder
                ->andWhere('translation.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        if (!empty($criteria['query'])) {
            $queryBuilder
                ->andWhere('translation.name LIKE :query')
                ->setParameter('query', '%' . $criteria['query'] . '%');
        }

        if (!empty($criteria['releasedAtFrom'])) {
            $dateCaclulator = new DateCalculator();
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gte('variant.releasedAt', ':releasedAtFrom'))
                ->setParameter('releasedAtFrom', $dateCaclulator->getDay($criteria['releasedAtFrom']));
        }
        if (!empty($criteria['releasedAtTo'])) {
            $dateCaclulator = new DateCalculator();
            $queryBuilder
                ->andWhere($queryBuilder->expr()->lte('variant.releasedAt', ':releasedAtTo'))
                ->setParameter('releasedAtTo', $dateCaclulator->getDay($criteria['releasedAtTo']));
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['updatedAt'] = 'desc';
        }

        $this->applySorting($queryBuilder, $sorting);

        if ($deleted) {
            $this->_em->getFilters()->disable('softdeleteable');
            $queryBuilder->andWhere('o.deletedAt IS NOT NULL');
        }

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
            ->where($queryBuilder->expr()->eq($this->getPropertyName('status'), ':published'))
            ->setParameter('published', Product::PUBLISHED);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}