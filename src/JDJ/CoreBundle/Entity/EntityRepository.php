<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 09/09/2014
 * Time: 00:43
 */

namespace JDJ\CoreBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class EntityRepository
 * @package JDJ\WebBundle\Entity
 *
 * TODO: Move on a CoreBundle
 */
class EntityRepository extends BaseEntityRepository
{
    /**
     * @var ArrayCollection
     */
    protected $joins;

    /**
     * EntityRepository constructor.
     *
     * @inheritdoc
     */
    public function __construct(EntityManager $em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->joins = new ArrayCollection();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $propertyName
     * @param string $alias
     */
    public function joinTo(QueryBuilder $queryBuilder, $propertyName, $alias)
    {
        if (!$this->joins->contains($alias))
        {
            $this->joins->add($alias);
            $queryBuilder
                ->join($this->getAlias().'.'.$propertyName, $alias);
        }
    }
}