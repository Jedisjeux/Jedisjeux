<?php

/*
 * This file is part of jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Taxon;
use App\Repository\TaxonRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

#[Route(path: '/counter')]
class CounterController extends AbstractController
{
    public function __construct(
        private readonly StatisticsProvider $statisticProvider,
        private readonly TaxonRepository $taxonRepository,
        private readonly CacheInterface $homepageCache,
    ) {
    }

    #[Route(path: '/', name: 'app_counter_index')]
    public function indexAction(): Response
    {
        // Only scalars are cached: Doctrine entities would come back detached
        $parameters = $this->homepageCache->get('community', fn (): array => array_merge($this->statisticProvider->getStatistics(), [
            'targetAudiences' => $this->normalizeTaxons($this->taxonRepository->findChildrenAsTreeByRootCode(Taxon::CODE_TARGET_AUDIENCE, false)),
            'mechanisms' => $this->normalizeTaxons($this->taxonRepository->findMostUsedByRootCode(Taxon::CODE_MECHANISM, 4)),
        ]));

        return $this->render('frontend/homepage/index/_counters.html.twig', $parameters);
    }

    /**
     * @param TaxonInterface[] $taxons
     *
     * @return array<array{code: string, name: string, slug: string}>
     */
    private function normalizeTaxons(array $taxons): array
    {
        return array_map(fn (TaxonInterface $taxon): array => [
            'code' => $taxon->getCode(),
            'name' => $taxon->getName(),
            'slug' => $taxon->getSlug(),
        ], $taxons);
    }
}
