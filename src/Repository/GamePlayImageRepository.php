<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class GamePlayImageRepository extends EntityRepository
{
    /**
     * @param string $localeCode
     *
     * @return QueryBuilder
     */
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('gamePlay')
            ->addSelect('product')
            ->addSelect('translation')
            ->join('o.gamePlay', 'gamePlay')
            ->join('gamePlay.product', 'product')
            ->join('product.translations', 'translation')
            ->where('translation.locale = :locale')
            ->setParameter('locale', $localeCode);

        return $queryBuilder;
    }
}
