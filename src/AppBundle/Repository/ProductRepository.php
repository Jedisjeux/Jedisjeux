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
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductRepository extends BaseProductRepository
{
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return parent::createQueryBuilder('o')
            ->select('o, option, variant, image, translation, mainTaxon')
            ->leftJoin('o.translations', 'translation')
            ->leftJoin('o.options', 'option')
            ->leftJoin('o.variants', 'variant')
            ->leftJoin('variant.images', 'image')
            ->leftJoin('o.mainTaxon', 'mainTaxon');
    }

    /**
     * @param string $slug
     *
     * @return ProductInterface|null
     */
    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('o.enabled = true')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
            ;
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
     * {@inheritdoc}
     */
    public function findOneByBarcode($barcode)
    {
        return $this->getQueryBuilder()
            ->join('o.barcodes', 'barcode')
            ->where('barcode.code = :barcode')
            ->setParameter('barcode', $barcode)
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
     * @param null|string $status
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [], $deleted = false, $status = Product::PUBLISHED)
    {
        $queryBuilder = $this->getQueryBuilder();

        if (!empty($criteria['name'])) {
            $queryBuilder
                ->andWhere('translation.name LIKE :name')
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        $status = (null === $status and !empty($criteria['status'])) ? $criteria['status'] : $status;

        if (null !== $status) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('status'), ':status'))
                ->setParameter('status', $status);
        }

        if (!empty($criteria['query'])) {
            $queryBuilder
                ->andWhere('translation.name LIKE :query')
                ->setParameter('query', '%' . $criteria['query'] . '%');
        }

        if (!empty($criteria['releasedAtFrom'])) {
            $dateCalculator = new DateCalculator();
            $queryBuilder
                ->andWhere($queryBuilder->expr()->gte('variant.releasedAt', ':releasedAtFrom'))
                ->setParameter('releasedAtFrom', $dateCalculator->getDay($criteria['releasedAtFrom']));
        }

        if (!empty($criteria['releasedAtTo'])) {
            $dateCalculator = new DateCalculator();
            $queryBuilder
                ->andWhere($queryBuilder->expr()->lte('variant.releasedAt', ':releasedAtTo'))
                ->setParameter('releasedAtTo', $dateCalculator->getDay($criteria['releasedAtTo']));
        }

        if (!empty($criteria['person'])) {
            $queryBuilder
                ->leftJoin('variant.designers', 'designer')
                ->leftJoin('variant.artists', 'artist')
                ->leftJoin('variant.publishers', 'publisher')
                ->andWhere($queryBuilder->expr()->orX(
                    'designer = :person',
                    'artist = :person',
                    'publisher = :person'
                ))
                ->setParameter('person', $criteria['person']);
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['createdAt'] = 'desc';
        }

        $this->applySorting($queryBuilder, $sorting);

        if ($deleted) {
            $this->_em->getFilters()->disable('softdeleteable');
            $queryBuilder->andWhere('o.deletedAt IS NOT NULL');
        }

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Count products categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     *
     * @return Pagerfanta
     */
    public function countByTaxon(TaxonInterface $taxon)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select('count(o)')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->leftJoin('o.taxons', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'mainTaxon = :taxon',
                ':left < mainTaxon.left AND mainTaxon.right < :right',
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        return $queryBuilder->getQuery()->getSingleScalarResult();
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

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return Pagerfanta
     */
    protected function getPaginator(QueryBuilder $queryBuilder)
    {
        // Use output walkers option in DoctrineORMAdapter should be false as it affects performance greatly (see #3775)
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, true, true));
    }
}