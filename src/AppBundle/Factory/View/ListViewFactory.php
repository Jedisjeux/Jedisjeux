<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory\View;

use AppBundle\Controller\AppView;
use AppBundle\Controller\ImageView;
use AppBundle\Controller\ListView;
use AppBundle\Document\AppDocument;
use AppBundle\Document\ImageDocument;
use ONGR\FilterManagerBundle\Search\SearchResponse;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ListViewFactory
{
    /**
     * @var string
     */
    private $appViewClass;

    /**
     * @var string
     */
    private $imageViewClass;

    /**
     * @param string $appViewClass
     * @param string $imageViewClass
     */
    public function __construct(string $appViewClass, string $imageViewClass)
    {
        $this->appViewClass = $appViewClass;
        $this->imageViewClass = $imageViewClass;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromSearchResponse(SearchResponse $response): ListView
    {
        $result = $response->getResult();
        $filters = $response->getFilters();
        /** @var ListView $listView */
        $listView = new ListView();
        $listView->filters = $filters;
        $pager = $filters['paginator']->getSerializableData()['pager'];
        $listView->page = $pager['current_page'];
        $listView->total = $pager['total_items'];
        $listView->pages = $pager['num_pages'];
        $listView->limit = $pager['limit'];
        foreach ($result as $document) {
            $listView->items[] = $this->getAppView($document);
        }

        return $listView;
    }

    /**
     * @param AppDocument $document
     *
     * @return AppView
     */
    private function getAppView(AppDocument $document): AppView
    {
        /** @var AppView $appView */
        $appView = new $this->appViewClass();
        $appView->type = $document->getType();
        $appView->name = $document->getName();
        $appView->createdAt = $document->getCreatedAt();

        if (null !== $document->getImage()) {
            $appView->image = $this->getImageView($document->getImage());
        }

        return $appView;
    }

    private function getImageView(ImageDocument $image): ImageView
    {
        /** @var ImageView $imageView */
        $imageView = new $this->imageViewClass();
        $imageView->path = $image->getPath();

        return $imageView;
    }
}
