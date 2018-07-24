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
use AppBundle\Controller\ArticleView;
use AppBundle\Controller\ImageView;
use AppBundle\Controller\ListView;
use AppBundle\Controller\PersonView;
use AppBundle\Controller\ProductView;
use AppBundle\Controller\TopicView;
use AppBundle\Document\AppDocument;
use AppBundle\Document\ArticleDocument;
use AppBundle\Document\ImageDocument;
use AppBundle\Document\PersonDocument;
use AppBundle\Document\ProductDocument;
use AppBundle\Document\TopicDocument;
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
    private $articleViewClass;

    /**
     * @var string
     */
    private $imageViewClass;

    /**
     * @var string
     */
    private $personViewClass;

    /**
     * @var string
     */
    private $productViewClass;

    /**
     * @var string
     */
    private $topicViewClass;

    /**
     * @param string $appViewClass
     * @param string $articleViewClass
     * @param string $imageViewClass
     * @param string $personViewClass
     * @param string $productViewClass
     * @param string $topicViewClass
     */
    public function __construct(
        string $appViewClass,
        string $articleViewClass,
        string $imageViewClass,
        string $personViewClass,
        string $productViewClass,
        string $topicViewClass
    ) {
        $this->appViewClass = $appViewClass;
        $this->articleViewClass = $articleViewClass;
        $this->imageViewClass = $imageViewClass;
        $this->personViewClass = $personViewClass;
        $this->productViewClass = $productViewClass;
        $this->topicViewClass = $topicViewClass;
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

        if (null !== $document->getArticle()) {
            $appView->article = $this->getArticleView($document->getArticle());
        }

        if (null !== $document->getImage()) {
            $appView->image = $this->getImageView($document->getImage());
        }

        if (null !== $document->getPerson()) {
            $appView->person = $this->getPersonView($document->getPerson());
        }

        if (null !== $document->getProduct()) {
            $appView->product = $this->getProductView($document->getProduct());
        }

        if (null !== $document->getTopic()) {
            $appView->topic = $this->getTopicView($document->getTopic());
        }

        return $appView;
    }

    /**
     * @param ArticleDocument $articleDocument
     *
     * @return ArticleView
     */
    private function getArticleView(ArticleDocument $articleDocument): ArticleView
    {
        /** @var ArticleView $articleView */
        $articleView = new $this->articleViewClass();
        $articleView->slug = $articleDocument->getSlug();

        return $articleView;
    }

    /**
     * @param ImageDocument $image
     *
     * @return ImageView
     */
    private function getImageView(ImageDocument $image): ImageView
    {
        /** @var ImageView $imageView */
        $imageView = new $this->imageViewClass();
        $imageView->path = $image->getPath();

        return $imageView;
    }

    /**
     * @param PersonDocument $personDocument
     *
     * @return PersonView
     */
    private function getPersonView(PersonDocument $personDocument): PersonView
    {
        /** @var PersonView $personView */
        $personView = new $this->personViewClass();
        $personView->slug = $personDocument->getSlug();

        return $personView;
    }

    /**
     * @param ProductDocument $productDocument
     *
     * @return ProductView
     */
    private function getProductView(ProductDocument $productDocument): ProductView
    {
        /** @var ProductView $productView */
        $productView = new $this->productViewClass();
        $productView->slug = $productDocument->getSlug();

        return $productView;
    }

    /**
     * @param TopicDocument $topicDocument
     *
     * @return TopicView
     */
    private function getTopicView(TopicDocument $topicDocument): TopicView
    {
        /** @var TopicView $topicView */
        $topicView = new $this->topicViewClass();
        $topicView->id = $topicDocument->getId();

        return $topicView;
    }
}
