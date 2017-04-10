<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/03/16
 * Time: 10:43
 */

namespace AppBundle\Repository;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->select('o', 'product', 'variant', 'productTranslation', 'image', 'topic', 'article')
            ->join('o.product', 'product')
            ->join('product.variants', 'variant')
            ->join('product.translations', 'productTranslation')
            ->leftJoin('variant.images', 'image')
            ->leftJoin('o.topic', 'topic')
            ->leftJoin('topic.article', 'article');

    }

    /**
     * @param string $locale
     * @param string|null $authorId
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder($locale, $authorId = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('product')
            ->addSelect('variant')
            ->addSelect('productTranslation')
            ->addSelect('image')
            ->addSelect('topic')
            ->addSelect('article')
            ->addSelect('players')
            ->addSelect('author')
            ->join('o.product', 'product')
            ->join('o.author', 'author')
            ->join('product.variants', 'variant')
            ->join('product.translations', 'productTranslation')
            ->leftJoin('variant.images', 'image')
            ->leftJoin('o.topic', 'topic')
            ->leftJoin('topic.article', 'article')
            ->leftJoin('o.players', 'players')
            ->andWhere('productTranslation.locale = :locale')
            ->setParameter('locale', $locale);

        if ($authorId) {
            $queryBuilder
                ->andWhere('author = :author')
                ->setParameter('author', $authorId);
        }

        return $queryBuilder;

    }

    /**
     * @param string $locale
     *
     * @return QueryBuilder
     */
    public function createCommentedListQueryBuilder($locale)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('product')
            ->addSelect('variant')
            ->addSelect('productTranslation')
            ->addSelect('image')
            ->addSelect('topic')
            ->addSelect('article')
            ->addSelect('players')
            ->join('o.product', 'product')
            ->join('product.variants', 'variant', Join::WITH, 'variant.position = 0')
            ->join('product.translations', 'productTranslation')
            ->leftJoin('variant.images', 'image', Join::WITH, 'image.main = :main')
            ->join('o.topic', 'topic')
            ->leftJoin('topic.article', 'article')
            ->leftJoin('o.players', 'players')
            ->andWhere('productTranslation.locale = :locale')
            ->groupBy('o.id')
            ->setParameter('locale', $locale)
            ->setParameter('main', true);

        return $queryBuilder;

    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
    {
        if (array_key_exists('hasTopic', $criteria)) {
            if ($criteria['hasTopic']) {
                $queryBuilder
                    ->andWhere('o.topic is not null');
            }
            unset($criteria['hasTopic']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @param array $criteria
     * @param array $sorting
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [])
    {
        $queryBuilder = $this->getQueryBuilder();

        if (empty($sorting)) {
            $sorting = array(
                'createdAt' => 'desc',
            );
        }

        $this->applyCriteria($queryBuilder, (array)$criteria);
        $this->applySorting($queryBuilder, (array)$sorting);

        return $this->getPaginator($queryBuilder);
    }

}