<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 17/03/2016
 * Time: 00:28
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ProductReviewRepository extends EntityRepository
{
    /**
     * {@inheritdoc}
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = [])
    {
        if (isset($criteria['hasComment']) and $criteria['hasComment'] !== '') {
            if ($criteria['hasComment']) {
                $queryBuilder
                    ->andWhere($this->getAlias().'.comment is not null');
            } else {
                // Sylius entity repository handles this case
                $criteria['comment'] = null;
            }
            unset($criteria['hasComment']);
        }


        parent::applyCriteria($queryBuilder, $criteria);
    }

}