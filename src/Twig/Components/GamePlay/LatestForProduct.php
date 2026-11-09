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

namespace App\Twig\Components\GamePlay;

use App\Entity\GamePlay;
use App\Repository\GamePlayRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'GamePlay:LatestForProduct', template: 'frontend/game_play/_list2.html.twig')]
final class LatestForProduct
{
    public int $productId;

    public int $count = 3;

    public function __construct(
        #[Autowire(service: 'app.repository.game_play')]
        private readonly GamePlayRepository $gamePlayRepository,
    ) {
    }

    /**
     * @return GamePlay[]
     */
    #[ExposeInTemplate('game_plays')]
    public function getGamePlays(): array
    {
        return $this->gamePlayRepository->findLatestByProductId($this->productId, $this->count);
    }
}
