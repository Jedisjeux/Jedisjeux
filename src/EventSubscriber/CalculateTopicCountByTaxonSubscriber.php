<?php

/*
 * This file is part of Jedisjeux projet.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\AppEvents;
use App\Entity\Topic;
use App\Updater\TopicCountByTaxonUpdater;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CalculateTopicCountByTaxonSubscriber implements EventSubscriberInterface
{
    /**
     * @var TopicCountByTaxonUpdater
     */
    protected $updater;

    /**
     * TopicCountByTaxonSubscriber constructor.
     *
     * @param TopicCountByTaxonUpdater $updater
     */
    public function __construct(TopicCountByTaxonUpdater $updater)
    {
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onTopicCreate(GenericEvent $event)
    {
        /** @var Topic $topic */
        $topic = $event->getSubject();

        $taxon = $topic->getMainTaxon();

        if (null === $taxon) {
            return;
        }

        $this->updater->update($taxon);
    }
}
