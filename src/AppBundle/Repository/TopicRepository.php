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

use AppBundle\Entity\Article;
use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Topic;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Elastica\Query;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicRepository extends EntityRepository
{
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
            ->addSelect('article')
            ->addSelect('gamePlay')
            ->addSelect('mainTaxon')
            ->addSelect('product')
            ->addSelect('productTranslation')
            ->join('o.author', 'customer')
            ->leftJoin('customer.user', 'user')
            ->leftJoin('customer.avatar', 'avatar')
            ->leftJoin('o.article', 'article')
            ->leftJoin('o.gamePlay', 'gamePlay')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->leftJoin('gamePlay.product', 'product')
            ->leftJoin('product.translations', 'productTranslation');
    }

    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->where($queryBuilder->expr()->orX(
                    'o.title like :query'
                ))
                ->setParameter('query', '%' . $criteria['query'] . '%');

            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @param string $localeCode
     * @param bool $showPrivate
     * @param TaxonInterface|null $taxon
     *
     * @return QueryBuilder
     */
    public function createFrontendListQueryBuilder(
        string $localeCode,
        bool $showPrivate = false,
        TaxonInterface $taxon = null
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('mainPost')
            ->addSelect('mainTaxon')
            ->addSelect('gamePlay')
            ->addSelect('article')
            ->addSelect('mainTaxonTranslation')
            ->addSelect('customer')
            ->addSelect('avatar')
            ->addSelect('user')
            ->join('o.author', 'customer')
            ->leftJoin('o.mainPost', 'mainPost')
            ->leftJoin('o.mainTaxon', 'mainTaxon')
            ->leftJoin('mainTaxon.translations', 'mainTaxonTranslation', Join::WITH, 'mainTaxonTranslation.locale = :localeCode')
            ->leftJoin('o.gamePlay', 'gamePlay')
            ->leftJoin('o.article', 'article')
            ->leftJoin('customer.avatar', 'avatar')
            ->leftJoin('customer.user', 'user')
            ->setParameter('localeCode', $localeCode)
        ;

        if (!$showPrivate) {
            $queryBuilder
                ->andWhere('o.mainTaxon is null or mainTaxon.public = :public')
                ->setParameter('public', true);
        }

        if (null !== $taxon) {
            $queryBuilder
                ->distinct()
                ->andWhere($queryBuilder->expr()->orX(
                    'mainTaxon = :taxon',
                    ':left < mainTaxon.left AND mainTaxon.right < :right AND mainTaxon.root = :root'
                ))
                ->setParameter('taxon', $taxon)
                ->setParameter('left', $taxon->getLeft())
                ->setParameter('right', $taxon->getRight())
                ->setParameter('root', $taxon->getRoot());
        }

        return $queryBuilder;
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     * @param bool $showPrivate
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = array(), $sorting = array(), $showPrivate = true)
    {
        $queryBuilder = $this->getQueryBuilder();

        if (!$showPrivate) {
            $queryBuilder
                ->andWhere('o.mainTaxon is null or mainTaxon.public = :public')
                ->setParameter('public', true);
        }

        if (!empty($criteria['query'])) {

            $queryBuilder
                ->where($queryBuilder->expr()->orX(
                    'user.username like :query',
                    'o.title like :query'
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

            $sorting['lastPostCreatedAt'] = 'desc';
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Create paginator for topics categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     * @param array $criteria
     * @param array $sorting
     * @param bool $showPrivate
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = array(), array $sorting = array(), $showPrivate = true)
    {
        $queryBuilder = $this->getQueryBuilder();

        if (!$showPrivate) {
            $queryBuilder
                ->andWhere('mainTaxon is null or mainTaxon.public = :public')
                ->setParameter('public', true);
        }

        $queryBuilder
            ->andWhere($queryBuilder->expr()->orX(
                'mainTaxon = :taxon',
                ':left < mainTaxon.left AND mainTaxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Count topics categorized under given taxon.
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
            ->innerJoin('o.mainTaxon', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param GamePlay $gamePlay
     *
     * @return Topic|null
     */
    public function findOneByGamePlay(GamePlay $gamePlay)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.gamePlay', 'gamePlay')
            ->where('gamePlay = :gamePlay')
            ->setParameter('gamePlay', $gamePlay);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Article $article
     *
     * @return Topic|null
     */
    public function findOneByArticle(Article $article)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.article', 'article')
            ->where('article = :article')
            ->setParameter('article', $article);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}