<?php

declare(strict_types=1);

namespace AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Document\TopicDocument;
use AppBundle\Entity\Topic;
use AppBundle\Event\TopicCreated;
use AppBundle\Event\TopicDeleted;
use AppBundle\Event\TopicUpdated;
use AppBundle\Factory\Document\TopicDocumentFactory;
use ONGR\ElasticsearchBundle\Result\DocumentIterator;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;

final class TopicProjector
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
     * @var TopicDocumentFactory
     */
    private $topicDocumentFactory;

    /**
     * @param Manager $elasticsearchManager
     * @param TopicDocumentFactory $topicDocumentFactory
     */
    public function __construct(
        Manager $elasticsearchManager,
        TopicDocumentFactory $topicDocumentFactory
    ) {
        $this->elasticsearchManager = $elasticsearchManager;
        $this->appDocumentRepository = $elasticsearchManager->getRepository(AppDocument::class);
        $this->topicDocumentFactory = $topicDocumentFactory;
    }

    /**
     * @param TopicCreated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handleTopicCreated(TopicCreated $event): void
    {
        $this->scheduleCreatingNewTopicDocuments($event->topic());

        $this->elasticsearchManager->commit();
    }

    /**
     * We create a new topic documents with updated data and remove old once
     *
     * @param TopicUpdated $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     * @throws \Exception
     */
    public function handleTopicUpdated(TopicUpdated $event): void
    {
        $topic = $event->topic();

        $this->scheduleRemovingOldTopicDocuments($topic);
        $this->scheduleCreatingNewTopicDocuments($topic);

        $this->elasticsearchManager->commit();
    }

    /**
     * We remove deleted topic
     *
     * @param TopicDeleted $event
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     */
    public function handleTopicDeleted(TopicDeleted $event): void
    {
        $topic = $event->topic();

        $this->scheduleRemovingOldTopicDocuments($topic);

        $this->elasticsearchManager->commit();
    }

    /**
     * @param Topic $topic
     *
     * @throws \Exception
     */
    private function scheduleCreatingNewTopicDocuments(Topic $topic): void
    {
        $this->elasticsearchManager->persist(
            $this->topicDocumentFactory->create(
                $topic
            )
        );
    }

    /**
     * @param Topic $topic
     */
    private function scheduleRemovingOldTopicDocuments(Topic $topic): void
    {
        /** @var DocumentIterator|TopicDocument[] $currentTopicDocuments */
        $currentTopicDocuments = $this->appDocumentRepository->findBy([
            'type' => AppDocument::TYPE_TOPIC,
            'code' => $topic->getCode(),
        ]);

        foreach ($currentTopicDocuments as $sameCodeTopicDocument) {
            $this->elasticsearchManager->remove($sameCodeTopicDocument);
        }
    }
}
