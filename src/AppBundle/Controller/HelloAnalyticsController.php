<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class HelloAnalyticsController extends Controller
{
    /**
     * @Route("/analytics", name="app_analytics")
     */
    public function indexAction()
    {
        $client = $this->getClient();
        $analytics = new \Google_Service_Analytics($client);
        $profile = $this->getParameter('google.analytics.profile_id');
        $results = $this->getResults($analytics, $profile);

        return $this->render('frontend/analytics/index.html.twig', [
            'results' => $results,
        ]);
    }

    protected function getClient()
    {
        return $this->get('app.google_analytics.client');
    }

    protected function getResults($analytics, $profileId) {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
        return $analytics->data_ga->get(
            'ga:' . $profileId,
            '3000daysAgo',
            'today',
            'ga:sessions');
    }
}
