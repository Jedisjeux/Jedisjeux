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

use App\Entity\GamePlayImage;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_game_play_image',
    resourceClass: GamePlayImage::class,
)]
final class GamePlayImageGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setLimits([12])
            ->setRepositoryMethod('createListQueryBuilder', [
                $this->locale,
            ])
            ->orderBy('createdAt', 'desc')
            ->withFields(
                Field::create('createdAt', 'twig')
                    ->setSortable(true),
            )
        ;
    }
}
