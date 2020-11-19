<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/counter")
 */
class CounterController extends AbstractController
{
    /** @var StatisticsProvider */
    private $statisticProvider;

    public function __construct(StatisticsProvider $statisticProvider)
    {
        $this->statisticProvider = $statisticProvider;
    }

    /**
     * @Route("/", name="app_counter_index")
     */
    public function indexAction(): Response
    {
        return $this->render('frontend/homepage/index/_counters.html.twig', $this->statisticProvider->getStatistics());
    }
}
