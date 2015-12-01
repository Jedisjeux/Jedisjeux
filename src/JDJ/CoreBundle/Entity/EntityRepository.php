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

/**
 * Class EntityRepository
 * @package JDJ\WebBundle\Entity
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
     * @param string $join
     * @param string $alias
     */
    public function joinTo(QueryBuilder $queryBuilder, $join, $alias)
    {
        if (!$this->joins->contains($alias))
        {
            $join = (false === strpos($join, '.')) ? $this->getAlias().'.'.$join : $join;

            $this->joins->add($alias);
            $queryBuilder
                ->join($join, $alias);
        }
    }
}