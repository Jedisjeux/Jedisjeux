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
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductRepository extends BaseProductRepository
{
    /**
     * @param $localeCode
     * @param bool $onlyPublished
     * @param array $criteria
     * @param TaxonInterface|null $taxon
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder($localeCode, $onlyPublished=true, array $criteria = [], TaxonInterface $taxon = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('translation')
            ->addSelect('variant')
            ->addSelect('image')
            ->addSelect('mainTaxon')
            ->leftJoin('o.translations', 'translation', Join::WITH, 'translation.locale = :localeCode')
            ->leftJoin('o.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->addGroupBy('o.id')
            ->setParameter('localeCode', $localeCode)
            ->setParameter('main', true);

        if ($onlyPublished) {
            $queryBuilder
                ->andWhere('o.status = :published')
                ->setParameter('published', Product::PUBLISHED);
        }

        if (null !== $taxon) {
            $queryBuilder
                ->innerJoin('o.taxons', 'taxon')
                ->andWhere($queryBuilder->expr()->orX(
                    'taxon = :taxon',
                    'o.mainTaxon = :taxon',
                    ':left < taxon.left AND taxon.right < :right'
                ))
                ->addGroupBy('o.id')
                ->setParameter('taxon', $taxon)
                ->setParameter('left', $taxon->getLeft())
                ->setParameter('right', $taxon->getRight());
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

        return $queryBuilder;
    }


    public function createListOfMostPlayedQueryBuilder($localeCode, CustomerInterface $author)
    {
        $queryBuilder = $this->createListQueryBuilder($localeCode);

        $queryBuilder
            ->addSelect('count(gamePlay.id) as HIDDEN gamePlayCount')
            ->join('o.gamePlays', 'gamePlay')
            ->andWhere('gamePlay.author = :author')
            ->andWhere($queryBuilder->expr()->between('gamePlay.playedAt', ':thisYearStart', ':thisYearEnd'))
            ->setParameter('author', $author)
            ->setParameter('thisYearStart', new \DateTime('first day of January'))
            ->setParameter('thisYearEnd', new \DateTime('last day of December'))
            ->addOrderBy('gamePlayCount', 'desc');

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryBuilderWithLocaleCode($localeCode)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('translation.locale = :localeCode')
            ->setParameter('localeCode', $localeCode);

        return $queryBuilder;
    }

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
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->setParameter('main', true);
    }

    /**
     * @param string $slug
     * @param bool $showUnpublished
     *
     * @return ProductInterface|null
     */
    public function findOneBySlug($slug, $showUnpublished = true)
    {
        $queryBuilder =  $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('o.enabled = true')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug);

        if (false === $showUnpublished) {
            $queryBuilder
                ->andWhere('o.status = :published')
                ->setParameter('published', Product::PUBLISHED);
        }

        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();
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
            ->addGroupBy('o.id')
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
        $queryBuilder
            ->addGroupBy('o.id');

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

        return $this->getPaginator($queryBuilder);
    }

    /**
     * {@inheritdoc}
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
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
}
