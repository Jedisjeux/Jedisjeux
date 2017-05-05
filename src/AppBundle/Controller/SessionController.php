<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\GoogleAnalytics\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SessionController extends Controller
{
    /**
     * @return Response
     */
    public function sessionCountPerDayAction()
    {
        $startAt = new \DateTime('first day of');
        $endAt = new \DateTime('today');

        $sessions = $this->getSessionService()->countSessionsPerDay($startAt, $endAt);

        return $this->render('backend/dashboard/_sessionCount.html.twig', array(
            'sessions' => $sessions,
        ));
    }

    /**
     * @return object|SessionService
     */
    protected function getSessionService()
    {
        return $this->get('app.google_analytics.session');
    }
}
