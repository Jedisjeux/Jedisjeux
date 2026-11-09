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

namespace App\Twig\Components\Homepage;

use App\Controller\StatisticsProvider;
use App\Entity\Taxon;
use App\Repository\TaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Counters of the community and entry points to the catalog, on the homepage.
 */
#[AsTwigComponent(name: 'Homepage:Community', template: 'frontend/homepage/index/_counters.html.twig')]
final class Community
{
    public int $articleCount = 0;

    public int $productCount = 0;

    public int $personCount = 0;

    public int $userCount = 0;

    public int $ratingCount = 0;

    /** @var array<array{code: string, name: string, slug: string}> */
    public array $targetAudiences = [];

    /** @var array<array{code: string, name: string, slug: string}> */
    public array $mechanisms = [];

    public function __construct(
        private readonly StatisticsProvider $statisticProvider,
        private readonly TaxonRepository $taxonRepository,
        private readonly CacheInterface $homepageCache,
    ) {
    }

    public function mount(): void
    {
        // Only scalars are cached: Doctrine entities would come back detached
        $community = $this->homepageCache->get('community', fn (): array => array_merge($this->statisticProvider->getStatistics(), [
            'targetAudiences' => $this->normalizeTaxons($this->taxonRepository->findChildrenAsTreeByRootCode(Taxon::CODE_TARGET_AUDIENCE, false)),
            'mechanisms' => $this->normalizeTaxons($this->taxonRepository->findMostUsedByRootCode(Taxon::CODE_MECHANISM, 4)),
        ]));

        $this->articleCount = (int) $community['articleCount'];
        $this->productCount = (int) $community['productCount'];
        $this->personCount = (int) $community['personCount'];
        $this->userCount = (int) $community['userCount'];
        $this->ratingCount = (int) $community['ratingCount'];
        $this->targetAudiences = $community['targetAudiences'];
        $this->mechanisms = $community['mechanisms'];
    }

    /**
     * @param TaxonInterface[] $taxons
     *
     * @return array<array{code: string, name: string, slug: string}>
     */
    private function normalizeTaxons(array $taxons): array
    {
        return array_map(fn (TaxonInterface $taxon): array => [
            'code' => (string) $taxon->getCode(),
            'name' => (string) $taxon->getName(),
            'slug' => (string) $taxon->getSlug(),
        ], $taxons);
    }
}
