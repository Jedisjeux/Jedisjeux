<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Grid\Frontend;

use App\Entity\ProductListItem;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_user_game_library',
    resourceClass: ProductListItem::class,
)]
final class UserGameLibraryGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setLimits([300])
            ->setRepositoryMethod('createQueryBuilderForGameLibrary', [
                'expr:service(\'sylius.repository.customer\').findOneByUsername($username)',
                $this->locale,
            ])
            ->extends('app_frontend_game_library')
            ->orderBy('box.height', 'desc')
        ;
    }
}
