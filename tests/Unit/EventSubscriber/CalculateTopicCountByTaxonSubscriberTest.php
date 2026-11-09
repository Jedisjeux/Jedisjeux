<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\AppEvents;
use App\Entity\Taxon;
use App\Entity\Topic;
use App\EventSubscriber\CalculateTopicCountByTaxonSubscriber;
use App\Updater\TopicCountByTaxonUpdater;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CalculateTopicCountByTaxonSubscriberTest extends TestCase
{
    private TopicCountByTaxonUpdater&MockObject $updater;

    private CalculateTopicCountByTaxonSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->updater = $this->createMock(TopicCountByTaxonUpdater::class);
        $this->subscriber = new CalculateTopicCountByTaxonSubscriber($this->updater);
    }

    public function testItSubscribesToTopicCreateEvent(): void
    {
        $this->assertSame([
            AppEvents::TOPIC_PRE_CREATE => 'onTopicCreate',
        ], CalculateTopicCountByTaxonSubscriber::getSubscribedEvents());
    }

    public function testItUpdatesWithTopicTaxon(): void
    {
        $taxon = $this->createStub(Taxon::class);
        $topic = $this->createStub(Topic::class);
        $topic->method('getMainTaxon')->willReturn($taxon);

        $this->updater->expects($this->once())->method('update')->with($taxon);

        $this->subscriber->onTopicCreate(new GenericEvent($topic));
    }

    public function testItDoesNotUpdateWhenTopicHasNoTaxon(): void
    {
        $topic = $this->createStub(Topic::class);
        $topic->method('getMainTaxon')->willReturn(null);

        $this->updater->expects($this->never())->method('update');

        $this->subscriber->onTopicCreate(new GenericEvent($topic));
    }
}
