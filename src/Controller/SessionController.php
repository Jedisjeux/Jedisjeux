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

use App\GoogleAnalytics\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SessionController extends AbstractController
{
    public function __construct(private SessionService $sessionService)
    {
    }

    /**
     * @return Response
     */
    public function sessionCountPerDayAction()
    {
        $startAt = new \DateTime('first day of');
        $endAt = new \DateTime('today');

        $sessions = $this->sessionService->countSessionsPerDay($startAt, $endAt);

        return $this->render('backend/dashboard/_sessionCount.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    /**
     * @return Response
     */
    public function sessionCountPerMonthAction()
    {
        $startAt = new \DateTime('first day of January');
        $endAt = new \DateTime('today');

        $sessions = $this->sessionService->countSessionsPerMonth($startAt, $endAt);

        return $this->render('backend/dashboard/_sessionCountPerMonth.html.twig', [
            'sessions' => $sessions,
        ]);
    }
}
