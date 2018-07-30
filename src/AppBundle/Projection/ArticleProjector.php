<?php

declare(strict_types=1);

namespace AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Document\ArticleDocument;
use AppBundle\Entity\Article;
use AppBundle\Event\ArticleCreated;
use AppBundle\Event\ArticleDeleted;
use AppBundle\Event\ArticleUpdated;
use AppBundle\Factory\Document\ArticleDocumentFactory;
use ONGR\ElasticsearchBundle\Result\DocumentIterator;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;

final class ArticleProjector
{
    /**
     * @var Manager
     */
    private $elasticsearchManager;

    /**
     * @var Repository
     */
    private $appDocumentRepository;

    /**
     * @var ArticleDocumentFactory
     */
    private $articleDocumentFactory;

    /**
     * @param Manager $elasticsearchManager
     * @param ArticleDocumentFactory $articleDocumentFactory
     */
    public function __construct(
        Manager $elasticsearchManager,
        ArticleDocumentFactory $articleDocumentFactory
    ) {
        $this->elasticsearchManager = $elasticsearchManager;
        $this->appDocumentRepository = $elasticsearchManager->getRepository(AppDocument::class);
        $this->articleDocumentFactory = $articleDocumentFactory;
    }

    /**
     * @param ArticleCreated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handleArticleCreated(ArticleCreated $event): void
    {
        $this->scheduleCreatingNewArticleDocuments($event->article());

        $this->elasticsearchManager->commit();
    }

    /**
     * We create a new article documents with updated data and remove old once
     *
     * @param ArticleUpdated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handleArticleUpdated(ArticleUpdated $event): void
    {
        $article = $event->article();

        $this->scheduleRemovingOldArticleDocuments($article);
        $this->scheduleCreatingNewArticleDocuments($article);

        $this->elasticsearchManager->commit();
    }

    /**
     * We remove deleted article
     *
     * @param ArticleDeleted $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     */
    public function handleArticleDeleted(ArticleDeleted $event): void
    {
        $article = $event->article();

        $this->scheduleRemovingOldArticleDocuments($article);

        $this->elasticsearchManager->commit();
    }

    /**
     * @param Article $article
     *
     * @throws \Exception
     */
    private function scheduleCreatingNewArticleDocuments(Article $article): void
    {
        $this->elasticsearchManager->persist(
            $this->articleDocumentFactory->create(
                $article
            )
        );
    }

    /**
     * @param Article $article
     */
    private function scheduleRemovingOldArticleDocuments(Article $article): void
    {
        /** @var DocumentIterator|ArticleDocument[] $currentArticleDocuments */
        $currentArticleDocuments = $this->appDocumentRepository->findBy([
            'type' => AppDocument::TYPE_ARTICLE,
            'code' => $article->getCode(),
        ]);

        foreach ($currentArticleDocuments as $sameCodeArticleDocument) {
            $this->elasticsearchManager->remove($sameCodeArticleDocument);
        }
    }
}
