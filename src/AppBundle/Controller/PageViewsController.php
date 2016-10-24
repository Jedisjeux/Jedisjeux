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

use AppBundle\GoogleAnalytics\PageViewsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PageViewsController extends Controller
{
    /**
     * @param string $pagePath
     *
     * @return JsonResponse
     */
    public function showAction($pagePath)
    {
        $pageViews = $this->getPageViewsService()->get($pagePath);

        return new JsonResponse([
            'view_count' => $pageViews,
        ]);
    }

    /**
     * @return PageViewsService
     */
    protected function getPageViewsService()
    {
        return $this->get('app.google_analytics.page_views');
    }
}
