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

use AppBundle\Factory\View\ListViewFactory;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use ONGR\FilterManagerBundle\Search\FilterManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ElasticSearchController
{
    /**
     * @var ViewHandlerInterface
     */
    private $restViewHandler;
    /**
     * @var ListViewFactory
     */
    private $listViewFactory;
    /**
     * @var FilterManagerInterface
     */
    private $filterManager;

    /**
     * @param ViewHandlerInterface $restViewHandler
     * @param ListViewFactory $listViewFactory
     * @param FilterManagerInterface $filterManager
     */
    public function __construct(ViewHandlerInterface $restViewHandler, ListViewFactory $listViewFactory, FilterManagerInterface $filterManager)
    {
        $this->restViewHandler = $restViewHandler;
        $this->listViewFactory = $listViewFactory;
        $this->filterManager = $filterManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $request->query->set('enabled', true);
        $response = $this->filterManager->handleRequest($request);

        return $this->restViewHandler->handle(
            View::create(
                $this->listViewFactory->createFromSearchResponse($response),
                Response::HTTP_OK
            )
        );
    }

}
