<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class GamePlayImageRepository extends EntityRepository
{
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
