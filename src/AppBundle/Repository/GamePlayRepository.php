<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/03/16
 * Time: 10:43
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayRepository extends EntityRepository
{
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

}