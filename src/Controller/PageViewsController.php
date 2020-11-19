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

use App\GoogleAnalytics\PageViewsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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
