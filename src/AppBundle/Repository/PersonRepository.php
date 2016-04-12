<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 14:11
 */

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use JDJ\CoreBundle\Entity\EntityRepository;
use Pagerfanta\Pagerfanta;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonRepository extends EntityRepository
{
    /**
     * @inheritdoc
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = array())
    {
        if (isset($criteria['query'])) {
            $queryBuilder
                ->andWhere($this->getAlias().'.slug like :query')
                ->setParameter('query', '%'.$criteria['query'].'%');
            unset($criteria['query']);
        }

        parent::applyCriteria($queryBuilder, $criteria);
    }

    /**
     * @inheritdoc
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = array())
    {
        if (isset($sorting['gameCount'])) {
            $queryBuilder->addSelect($queryBuilder->expr()->sum(
                    "SIZE(".$this->getAlias().".designerProducts)",
                    "SIZE(".$this->getAlias().".publisherProducts)",
                    "SIZE(".$this->getAlias().".artistProducts)").
                " as HIDDEN gameCount");
            $queryBuilder->addOrderBy("gameCount", $sorting['gameCount']);
            unset($sorting['gameCount']);
        }

        parent::applySorting($queryBuilder, $sorting);
    }

    /**
     * Create paginator for products categorized under given taxon.
     *
     * @param TaxonInterface $taxon
     * @param array $criteria
     * @param array $sorting
     *
     * @return Pagerfanta
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, array $criteria = [], array $sorting = [])
    {
        $queryBuilder = $this->getCollectionQueryBuilder();
        $queryBuilder
            ->innerJoin($this->getAlias().'.taxons', 'taxon')
            ->andWhere($queryBuilder->expr()->orX(
                'taxon = :taxon',
                ':left < taxon.left AND taxon.right < :right'
            ))
            ->setParameter('taxon', $taxon)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight());

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNbResults()
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->select($queryBuilder->expr()->count($this->getAlias()));
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}