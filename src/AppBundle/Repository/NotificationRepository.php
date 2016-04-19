<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 19/04/16
 * Time: 12:06
 */

namespace AppBundle\Repository;

use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\User\Model\CustomerInterface;

class NotificationRepository extends EntityRepository
{
    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     * @param CustomerInterface|int $customer
     *
     * @return Pagerfanta
     */
    public function createForCustomerFilterPaginator($criteria = [], $sorting = [], $customer)
    {
        $queryBuilder = parent::getCollectionQueryBuilder()
            ->addSelect('recipient')
            ->join('o.recipient', 'recipient')
            ->where('recipient = :recipient')
            ->setParameter('recipient', $customer);

        $this->applyCriteria($queryBuilder, (array)$criteria);

        if (empty($sorting)) {
            if (!is_array($sorting)) {
                $sorting = [];
            }

            $sorting['createdAt'] = 'desc';
        }

        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }
}