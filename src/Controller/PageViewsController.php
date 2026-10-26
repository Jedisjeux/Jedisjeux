<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\GoogleAnalytics\PageViewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageViewsController extends AbstractController
{
    public function __construct(private PageViewsService $pageViewsService)
    {
    }

    /**
     * @param string $pagePath
     *
     * @return JsonResponse
     */
    public function showAction($pagePath)
    {
        $pageViews = $this->pageViewsService->get($pagePath);

        return new JsonResponse([
            'view_count' => $pageViews,
        ]);
    }
}
