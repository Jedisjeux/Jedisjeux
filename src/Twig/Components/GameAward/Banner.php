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

namespace App\Twig\Components\GameAward;

use App\Entity\GameAward;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'GameAward:Banner', template: 'frontend/year_award/indexByAward/_bannerContent.html.twig')]
final class Banner
{
    public string $slug;

    /**
     * @param RepositoryInterface<GameAward> $gameAwardRepository
     */
    public function __construct(
        #[Autowire(service: 'app.repository.game_award')]
        private readonly RepositoryInterface $gameAwardRepository,
    ) {
    }

    #[ExposeInTemplate('game_award')]
    public function getGameAward(): GameAward
    {
        $gameAward = $this->gameAwardRepository->findOneBy(['slug' => $this->slug]);

        if (!$gameAward instanceof GameAward) {
            throw new NotFoundHttpException(sprintf('Game award "%s" does not exist.', $this->slug));
        }

        return $gameAward;
    }
}
