<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 20/06/2016
 * Time: 14:20
 */

namespace AppBundle\Repository;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonRepository extends BaseTaxonRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation');
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenAsTree(TaxonInterface $taxon, $showPrivate = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder
            ->addSelect('children')
            ->leftJoin('o.children', 'children')
            ->andWhere('o.parent = :parent')
            ->addOrderBy('o.root')
            ->addOrderBy('o.left')
            ->setParameter('parent', $taxon);

        if (!$showPrivate) {
            $queryBuilder
                ->andWhere('o.public = :public')
                ->setParameter('public', true);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenAsTreeByRootCode($code, $showPrivate = true)
    {
        /** @var TaxonInterface|null $root */
        $root = $this->findOneBy(['code' => $code]);

        if (null === $root) {
            return [];
        }

        return $this->findChildrenAsTree($root, $showPrivate);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByNameAndRoot($name, TaxonInterface $root)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->where('translation.name = :name')
            ->andWhere('o.root = :root')
            ->setParameter('name', $name)
            ->setParameter('root', $root)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $slug
     *
     * @return TaxonInterface
     */
    public function findOneBySlug($slug)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->where('translation.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}