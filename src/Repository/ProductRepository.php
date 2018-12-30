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

use App\Entity\Person;
use App\Entity\Product;
use App\Entity\YearAward;
use App\Utils\DateCalculator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use SyliusLabs\AssociationHydrator\AssociationHydrator;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductRepository extends BaseProductRepository
{
    /**
     * @var AssociationHydrator
     */
    private $associationHydrator;

    /**
     * @param EntityManager         $entityManager
     * @param Mapping\ClassMetadata $class
     */
    public function __construct(EntityManager $entityManager, Mapping\ClassMetadata $class)
    {
        parent::__construct($entityManager, $class);

        $this->associationHydrator = new AssociationHydrator($entityManager, $class);
    }

    /**
     * @param $localeCode
     * @param bool $onlyPublished
     * @param array $criteria
     * @param TaxonInterface|null $taxon
     * @param Person|null $person
     * @param YearAward|null $yearAward
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(
        $localeCode,
        $onlyPublished = true,
        array $criteria = [],
        TaxonInterface $taxon = null,
        Person $person = null,
        YearAward $yearAward = null
    ) {
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
            ->setParameter('localeCode', $localeCode)
            ->setParameter('main', true);

        if ($onlyPublished) {
            $queryBuilder
                ->andWhere('o.status = :published')
                ->setParameter('published', Product::PUBLISHED);
        }

        if (null !== $taxon) {
            $queryBuilder
                ->distinct()
                ->leftJoin('o.taxons', 'taxon')
                ->andWhere($queryBuilder->expr()->orX(
                    'mainTaxon = :taxon',
                    ':left < mainTaxon.left AND mainTaxon.right < :right AND mainTaxon.root = :root',
                    'taxon = :taxon',
                    ':left < taxon.left AND taxon.right < :right AND taxon.root = :root'
                ))
                ->setParameter('taxon', $taxon)
                ->setParameter('left', $taxon->getLeft())
                ->setParameter('right', $taxon->getRight())
                ->setParameter('root', $taxon->getRoot());
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

        if (null !== $person) {
            $criteria['person'] = $person;
        }

        if (!empty($criteria['person'])) {
            $queryBuilder
                ->distinct()
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

        if (null !== $yearAward) {
            $queryBuilder
                ->innerJoin('o.yearAwards', 'yearAward')
                ->andWhere($queryBuilder->expr()->eq('yearAward', ':yearAward'))
                ->setParameter('yearAward', $yearAward);
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
     * @param string $locale
     * @param string $slug
     * @param bool   $showUnpublished
     *
     * @return ProductInterface|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySlug(string $locale, string $slug, $showUnpublished = true)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->andWhere('o.enabled = true')
            ->andWhere('translation.slug = :slug')
            ->setParameter('locale', $locale)
            ->setParameter('slug', $slug);

        if (false === $showUnpublished) {
            $queryBuilder
                ->andWhere('o.status = :published')
                ->setParameter('published', Product::PUBLISHED);
        }

        $product = $queryBuilder->getQuery()->getOneOrNullResult();

        $this->associationHydrator->hydrateAssociations($product, [
            'variants',
            'variants.images',
            'taxons',
            'taxons.translations',
            'variants.artists',
            'variants.designers',
            'variants.publishers',
        ]);

        return $product;
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
     * @return int
     */
    public function countByTaxon(TaxonInterface $taxon): int
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->select('count(distinct o)')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->leftJoin('o.taxons', 'taxon')
            ->andWhere($queryBuilder->expr()->eq($this->getPropertyName('status'), ':published'))
            ->andWhere($queryBuilder->expr()->orX(
                'mainTaxon = :taxon',
                ':left < mainTaxon.left AND mainTaxon.right < :right AND mainTaxon.root = :root',
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right AND taxon.root = :root'
            ))
            ->setParameter('published', Product::PUBLISHED)
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight())
            ->setParameter('root', $taxon->getRoot());

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
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
