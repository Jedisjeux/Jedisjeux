<?php

namespace spec\App\EventSubscriber;

use App\Entity\Taxon;
use App\Entity\Topic;
use App\EventSubscriber\CalculateTopicCountByTaxonSubscriber;
use App\Updater\TopicCountByTaxonUpdater;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculateTopicCountByTaxonSubscriberSpec extends ObjectBehavior
{
    function let(TopicCountByTaxonUpdater $updater)
    {
        $this->beConstructedWith($updater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CalculateTopicCountByTaxonSubscriber::class);
    }

    function it_updates_with_topic_taxon(
        TopicCountByTaxonUpdater $updater,
        GenericEvent $event,
        Taxon $taxon,
        Topic $topic
    ): void {
        $event->getSubject()->willReturn($topic);
        $topic->getMainTaxon()->willReturn($taxon);

        $updater->update($taxon)->shouldBeCalled();

        $this->onTopicCreate($event);
    }

    function it_does_not_updates_when_topic_has_no_taxon(
        TopicCountByTaxonUpdater $updater,
        GenericEvent $event,
        Topic $topic
    ): void {
        $event->getSubject()->willReturn($topic);
        $topic->getMainTaxon()->willReturn(null);

        $updater->update(Argument::type(Taxon::class))->shouldNotBeCalled();

        $this->onTopicCreate($event);
    }
}
