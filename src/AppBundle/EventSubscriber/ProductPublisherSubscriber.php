<?php

declare(strict_types=1);

namespace AppBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use SimpleBus\Message\Bus\MessageBus;
use Sylius\Component\Product\Model\ProductInterface;
use AppBundle\Event\ProductCreated;
use AppBundle\Event\ProductDeleted;
use AppBundle\Event\ProductUpdated;
use Sylius\Component\Product\Model\ProductTranslationInterface;

final class ProductPublisherSubscriber implements EventSubscriber
{
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @var ProductInterface[]
     */
    private $scheduledInsertions = [];

    /**
     * @var ProductInterface[]
     */
    private $scheduledUpdates = [];

    /**
     * @var ProductInterface[]
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
            if ($entity instanceof ProductInterface && !isset($this->scheduledInsertions[$entity->getCode()])) {
                $this->scheduledInsertions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getProductFromEntity($entity);

            if ($entity instanceof ProductInterface && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledUpdates = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityUpdates();

        foreach ($scheduledUpdates as $entity) {
            $entity = $this->getProductFromEntity($entity);

            if ($entity instanceof ProductInterface && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }

        $scheduledDeletions = $event->getEntityManager()->getUnitOfWork()->getScheduledEntityDeletions();

        foreach ($scheduledDeletions as $entity) {
            if ($entity instanceof ProductInterface && !isset($this->scheduledDeletions[$entity->getCode()])) {
                $this->scheduledDeletions[$entity->getCode()] = $entity;

                continue;
            }

            $entity = $this->getProductFromEntity($entity);

            if ($entity instanceof ProductInterface && !isset($this->scheduledUpdates[$entity->getCode()])) {
                $this->scheduledUpdates[$entity->getCode()] = $entity;
            }
        }
    }

    /**
     * @param PostFlushEventArgs $event
     */
    public function postFlush(PostFlushEventArgs $event): void
    {
        foreach ($this->scheduledInsertions as $product) {
            $this->eventBus->handle(ProductCreated::occur($product));
        }

        $scheduledUpdates = array_diff_key(
            $this->scheduledUpdates,
            $this->scheduledInsertions,
            $this->scheduledDeletions
        );

        foreach ($scheduledUpdates as $product) {
            $this->eventBus->handle(ProductUpdated::occur($product));
        }

        foreach ($this->scheduledDeletions as $product) {
            $this->eventBus->handle(ProductDeleted::occur($product));
        }

        $this->scheduledInsertions = [];
        $this->scheduledUpdates = [];
        $this->scheduledDeletions = [];
    }

    /**
     * @param object $entity
     *
     * @return ProductInterface|null
     */
    private function getProductFromEntity($entity): ?ProductInterface
    {
        if ($entity instanceof ProductInterface) {
            return $entity;
        }

        if ($entity instanceof ProductTranslationInterface) {
            return $entity->getTranslatable();
        }

        return null;
    }
}
