<?php

declare(strict_types=1);

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Topic;
use AppBundle\Event\TopicCreated;
use AppBundle\Event\TopicDeleted;
use AppBundle\Event\TopicUpdated;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use SimpleBus\Message\Bus\MessageBus;

final class TopicPublisherSubscriber implements EventSubscriber
{
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @var Topic[]
     */
    private $scheduledInsertions = [];

    /**
     * @var Topic[]
     */
    private $scheduledUpdates = [];

    /**
     * @var Topic[]
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
            if ($entity instanceof Topic && !isset($this->scheduledInsertions[$entity->getCode()])) {
                $this->scheduledInsertions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getTopicFromEntity($entity);

            if ($entity instanceof Topic && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledUpdates = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityUpdates();

        foreach ($scheduledUpdates as $entity) {
            $entity = $this->getTopicFromEntity($entity);

            if ($entity instanceof Topic && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledDeletions = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityDeletions();

        foreach ($scheduledDeletions as $entity) {
            if ($entity instanceof Topic && !isset($this->scheduledDeletions[$entity->getCode()])) {
                $this->scheduledDeletions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getTopicFromEntity($entity);

            if ($entity instanceof Topic && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }
    }

    /**
     * @param PostFlushEventArgs $event
     */
    public function postFlush(PostFlushEventArgs $event): void
    {
        foreach ($this->scheduledInsertions as $person) {
            $this->eventBus->handle(TopicCreated::occur($person));
        }

        $scheduledUpdates = array_diff_key(
            $this->scheduledUpdates,
            $this->scheduledInsertions,
            $this->scheduledDeletions
        );

        foreach ($scheduledUpdates as $person) {
            $this->eventBus->handle(TopicUpdated::occur($person));
        }

        foreach ($this->scheduledDeletions as $person) {
            $this->eventBus->handle(TopicDeleted::occur($person));
        }

        $this->scheduledInsertions = [];
        $this->scheduledUpdates = [];
        $this->scheduledDeletions = [];
    }

    /**
     * @param object $entity
     *
     * @return Topic|null
     */
    private function getTopicFromEntity($entity): ?Topic
    {
        if ($entity instanceof Topic) {
            return $entity;
        }

        return null;
    }
}
