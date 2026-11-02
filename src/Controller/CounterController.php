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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/counter")
 */
class CounterController extends AbstractController
{
    public function __construct(
        private readonly StatisticsProvider $statisticProvider,
        private readonly TaxonRepository $taxonRepository,
    ) {
    }

    /**
     * @Route("/", name="app_counter_index")
     */
    public function indexAction(): Response
    {
        return $this->render('frontend/homepage/index/_counters.html.twig', array_merge($this->statisticProvider->getStatistics(), [
            'targetAudiences' => $this->taxonRepository->findChildrenAsTreeByRootCode(Taxon::CODE_TARGET_AUDIENCE, false),
            'mechanisms' => $this->taxonRepository->findMostUsedByRootCode(Taxon::CODE_MECHANISM, 4),
        ]));
    }
}
