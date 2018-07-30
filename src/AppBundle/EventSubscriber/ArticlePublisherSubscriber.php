<?php

declare(strict_types=1);

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Article;
use AppBundle\Event\ArticleCreated;
use AppBundle\Event\ArticleDeleted;
use AppBundle\Event\ArticleUpdated;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use SimpleBus\Message\Bus\MessageBus;

final class ArticlePublisherSubscriber implements EventSubscriber
{
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @var Article[]
     */
    private $scheduledInsertions = [];

    /**
     * @var Article[]
     */
    private $scheduledUpdates = [];

    /**
     * @var Article[]
     */
    private $scheduledDeletions = [];

    /**
     * @param MessageBus $eventBus
     */
    public function __construct(MessageBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }

    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event): void
    {
        $scheduledInsertions = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions();

        foreach ($scheduledInsertions as $entity) {
            if ($entity instanceof Article && !isset($this->scheduledInsertions[$entity->getCode()])) {
                $this->scheduledInsertions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getArticleFromEntity($entity);

            if ($entity instanceof Article && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledUpdates = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityUpdates();

        foreach ($scheduledUpdates as $entity) {
            $entity = $this->getArticleFromEntity($entity);

            if ($entity instanceof Article && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledDeletions = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityDeletions();

        foreach ($scheduledDeletions as $entity) {
            if ($entity instanceof Article && !isset($this->scheduledDeletions[$entity->getCode()])) {
                $this->scheduledDeletions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getArticleFromEntity($entity);

            if ($entity instanceof Article && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }
    }

    /**
     * @param PostFlushEventArgs $event
     */
    public function postFlush(PostFlushEventArgs $event): void
    {
        foreach ($this->scheduledInsertions as $article) {
            $this->eventBus->handle(ArticleCreated::occur($article));
        }

        $scheduledUpdates = array_diff_key(
            $this->scheduledUpdates,
            $this->scheduledInsertions,
            $this->scheduledDeletions
        );

        foreach ($scheduledUpdates as $article) {
            $this->eventBus->handle(ArticleUpdated::occur($article));
        }

        foreach ($this->scheduledDeletions as $article) {
            $this->eventBus->handle(ArticleDeleted::occur($article));
        }

        $this->scheduledInsertions = [];
        $this->scheduledUpdates = [];
        $this->scheduledDeletions = [];
    }

    /**
     * @param object $entity
     *
     * @return Article|null
     */
    private function getArticleFromEntity($entity): ?Article
    {
        if ($entity instanceof Article) {
            return $entity;
        }

        return null;
    }
}
