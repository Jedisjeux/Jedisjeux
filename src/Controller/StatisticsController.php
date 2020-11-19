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

class StatisticsController extends AbstractController
{
    /** @var StatisticsProvider */
    private $statisticProvider;

    public function __construct(StatisticsProvider $statisticProvider)
    {
        $this->statisticProvider = $statisticProvider;
    }

    public function indexAction(): Response
    {
        return $this->render('backend/index.html.twig', $this->statisticProvider->getStatistics());
    }
}
