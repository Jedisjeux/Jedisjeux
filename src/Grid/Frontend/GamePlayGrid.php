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

use App\Entity\GamePlay;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Component\Grid\Attribute\AsGrid;
use Sylius\Component\Grid\Builder\GridBuilderInterface;

#[AsGrid(
    name: 'app_frontend_game_play',
    resourceClass: GamePlay::class,
)]
final class GamePlayGrid
{
    public function __construct(
        private readonly string $locale,
    ) {
    }

    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setRepositoryMethod('createFrontendListQueryBuilder', [
                'locale' => $this->locale,
                'criteria' => 'expr:service(\'request_stack\').getCurrentRequest().get(\'criteria\', [])',
                'productSlug' => '$productSlug',
            ])
            ->orderBy('createdAt', 'desc')
            ->withFields(
                Field::create('createdAt', 'twig')
                    ->setSortable(true),
                Field::create('topic.postCount', 'twig')
                    ->setSortable(true),
            )
            ->withFilters(
                Filter::create('topic', 'frontend_comment'),
                Filter::create('imageCount', 'frontend_image'),
            )
        ;
    }
}
