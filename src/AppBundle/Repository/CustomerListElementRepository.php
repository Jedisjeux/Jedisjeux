<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 13/04/2016
 * Time: 13:14
 */

namespace AppBundle\Repository;

use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerListElementRepository extends EntityRepository
{
    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     * @param int|CustomerInterface $customer
     * @param string $code
     *
     * @return Pagerfanta
     */
    public function createFilterPaginator($criteria = [], $sorting = [], $customer, $code)
    {
        $queryBuilder = parent::getCollectionQueryBuilder()
            ->join('o.customerList', 'customerList')
            ->andWhere('customerList.code = :code')
            ->andWhere('customerList.customer = :customer')
            ->setParameter('code', $code)
            ->setParameter('customer', $customer);

        if (!empty($criteria['query'])) {
            $queryBuilder
                ->andWhere('user.username LIKE :query')
                ->setParameter('query', '%' . $criteria['query'] . '%');
        }

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['updatedAt'] = 'desc';
        }

        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }
}