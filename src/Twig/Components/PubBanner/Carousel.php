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

namespace App\Twig\Components\PubBanner;

use App\Entity\PubBanner;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsTwigComponent(name: 'PubBanner:Carousel', template: 'frontend/pub_banner/_randomList.html.twig')]
final class Carousel
{
    /**
     * @param RepositoryInterface<PubBanner> $pubBannerRepository
     */
    public function __construct(
        #[Autowire(service: 'app.repository.pub_banner')]
        private readonly RepositoryInterface $pubBannerRepository,
    ) {
    }

    /**
     * @return PubBanner[]
     */
    #[ExposeInTemplate('pub_banners')]
    public function getPubBanners(): array
    {
        return $this->pubBannerRepository->findAll();
    }
}
