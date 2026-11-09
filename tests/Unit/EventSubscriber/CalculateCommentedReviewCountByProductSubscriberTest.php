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

use App\Entity\Product;
use App\EventSubscriber\CalculateCommentedReviewCountByProductSubscriber;
use App\Updater\CommentedReviewCountByProductUpdater;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CalculateCommentedReviewCountByProductSubscriberTest extends TestCase
{
    private CommentedReviewCountByProductUpdater&MockObject $updater;

    private CalculateCommentedReviewCountByProductSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->updater = $this->createMock(CommentedReviewCountByProductUpdater::class);
        $this->subscriber = new CalculateCommentedReviewCountByProductSubscriber($this->updater);
    }

    public function testItSubscribesToDoctrineEvents(): void
    {
        $this->assertSame([
            Events::prePersist,
            Events::preUpdate,
        ], $this->subscriber->getSubscribedEvents());
    }

    public function testItUpdatesCommentedReviewCountOnProductCreateEvent(): void
    {
        $product = $this->createStub(Product::class);
        $this->updater->expects($this->once())->method('update')->with($product);

        $this->subscriber->prePersist($this->createArgs($product));
    }

    public function testItUpdatesCommentedReviewCountOnProductUpdateEvent(): void
    {
        $product = $this->createStub(Product::class);
        $this->updater->expects($this->once())->method('update')->with($product);

        $this->subscriber->preUpdate($this->createArgs($product));
    }

    private function createArgs(object $object): LifecycleEventArgs
    {
        $args = $this->createStub(LifecycleEventArgs::class);
        $args->method('getObject')->willReturn($object);

        return $args;
    }
}
