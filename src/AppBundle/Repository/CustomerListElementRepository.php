<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 13/04/2016
 * Time: 13:14
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use Pagerfanta\Pagerfanta;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\User\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerListElementRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this->createQueryBuilder('o');
    }

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
        $queryBuilder = $this->getQueryBuilder()
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

    /**
     * Create product filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     * @param int|CustomerInterface $customer
     * @param string $code
     *
     * @return Pagerfanta
     */
    public function createProductFilterPaginator($criteria = [], $sorting = [], $customer, $code)
    {
        $queryBuilder = $this->getQueryBuilder()
            ->addSelect('product')
            ->addSelect('variant')
            ->addSelect('image')
            ->addSelect('translation')
            ->join('o.customerList', 'customerList')
            ->join('o.product', 'product')
            ->join('product.variants', 'variant')
            ->join('product.translations', 'translation')
            ->join('variant.images', 'image')
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

    /**
     * Create product filter paginator.
     *
     * @param TaxonInterface $taxon
     * @param array $criteria
     * @param array $sorting
     * @param int|CustomerInterface $customer
     * @param string $code
     *
     * @return Pagerfanta
     */
    public function createProductFilterByTaxonPaginator(TaxonInterface $taxon, $criteria = [], $sorting = [], $customer, $code)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->addSelect('product')
            ->addSelect('variant')
            ->addSelect('image')
            ->addSelect('translation')
            ->join('o.customerList', 'customerList')
            ->join('o.product', 'product')
            ->join('product.variants', 'variant')
            ->join('product.translations', 'translation')
            ->join('variant.images', 'image')
            ->join('product.taxons', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->andWhere('customerList.code = :code')
            ->andWhere('customerList.customer = :customer')
            ->setParameter('code', $code)
            ->setParameter('customer', $customer)
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

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