<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleReviewRepository extends EntityRepository
{
    /**
     * @param int $articleId
     *
     * @return QueryBuilder
     */
    public function createQueryBuilderByArticleId($articleId)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->join('o.reviewSubject', 'article')
            ->andWhere('article = :article')
            ->setParameter('article', $articleId);

        return $queryBuilder;
    }
}
