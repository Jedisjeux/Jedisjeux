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

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonRepository extends BaseTaxonRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createRootListQueryBuilder($localeCode)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('translation.locale = :localeCode')
            ->andWhere($queryBuilder->expr()->isNull($this->getPropertyName('parent')))
            ->setParameter('localeCode', $localeCode);

        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createChildrenListQueryBuilder($localeCode, TaxonInterface $taxon)
    {
        $root = $taxon->isRoot() ? $taxon : $taxon->getRoot();

        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('translation.locale = :localeCode')
            ->andWhere($queryBuilder->expr()->eq('o.root', ':root'))
            ->andWhere($queryBuilder->expr()->lt('o.right', ':right'))
            ->andWhere($queryBuilder->expr()->gt('o.left', ':left'))
            ->setParameter('localeCode', $localeCode)
            ->setParameter('root', $root)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight())
            ->orderBy('o.left')
        ;

        return $queryBuilder;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation');
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenAsTree(TaxonInterface $taxon, $showPrivate = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->addSelect('children')
            ->leftJoin('o.children', 'children')
            ->andWhere('o.parent = :parent')
            ->addOrderBy('o.root')
            ->addOrderBy('o.left')
            ->setParameter('parent', $taxon);

        if (!$showPrivate) {
            $queryBuilder
                ->andWhere('o.public = :public')
                ->setParameter('public', true);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenAsTreeByRootCode($code, $showPrivate = true)
    {
        /** @var TaxonInterface|null $root */
        $root = $this->findOneBy(['code' => $code]);

        if (null === $root) {
            return [];
        }

        return $this->findChildrenAsTree($root, $showPrivate);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByNameAndRoot($name, TaxonInterface $root)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->where('translation.name = :name')
            ->andWhere('o.root = :root')
            ->setParameter('name', $name)
            ->setParameter('root', $root)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
