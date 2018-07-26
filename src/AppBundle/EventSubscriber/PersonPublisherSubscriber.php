<?php

declare(strict_types=1);

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Person;
use AppBundle\Event\PersonCreated;
use AppBundle\Event\PersonDeleted;
use AppBundle\Event\PersonUpdated;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use SimpleBus\Message\Bus\MessageBus;

final class PersonPublisherSubscriber implements EventSubscriber
{
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @var Person[]
     */
    private $scheduledInsertions = [];

    /**
     * @var Person[]
     */
    private $scheduledUpdates = [];

    /**
     * @var Person[]
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
            if ($entity instanceof Person && !isset($this->scheduledInsertions[$entity->getCode()])) {
                $this->scheduledInsertions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getPersonFromEntity($entity);

            if ($entity instanceof Person && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledUpdates = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityUpdates();

        foreach ($scheduledUpdates as $entity) {
            $entity = $this->getPersonFromEntity($entity);

            if ($entity instanceof Person && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledDeletions = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityDeletions();

        foreach ($scheduledDeletions as $entity) {
            if ($entity instanceof Person && !isset($this->scheduledDeletions[$entity->getCode()])) {
                $this->scheduledDeletions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getPersonFromEntity($entity);

            if ($entity instanceof Person && !isset($this->scheduledUpdates[$entity->getCode()])) {
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
            $this->eventBus->handle(PersonCreated::occur($person));
        }

        $scheduledUpdates = array_diff_key(
            $this->scheduledUpdates,
            $this->scheduledInsertions,
            $this->scheduledDeletions
        );

        foreach ($scheduledUpdates as $person) {
            $this->eventBus->handle(PersonUpdated::occur($person));
        }

        foreach ($this->scheduledDeletions as $person) {
            $this->eventBus->handle(PersonDeleted::occur($person));
        }

        $this->scheduledInsertions = [];
        $this->scheduledUpdates = [];
        $this->scheduledDeletions = [];
    }

    /**
     * @param object $entity
     *
     * @return Person|null
     */
    private function getPersonFromEntity($entity): ?Person
    {
        if ($entity instanceof Person) {
            return $entity;
        }

        return null;
    }
}
