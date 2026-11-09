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

use App\Entity\ProductInterface;
use App\Entity\ProductReview;
use App\EventSubscriber\CalculateReviewCountByProductSubscriber;
use App\Updater\ReviewCountByProductUpdater;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CalculateReviewCountByProductSubscriberTest extends TestCase
{
    private ReviewCountByProductUpdater&MockObject $updater;

    private EntityManagerInterface&MockObject $entityManager;

    private CalculateReviewCountByProductSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->updater = $this->createMock(ReviewCountByProductUpdater::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->subscriber = new CalculateReviewCountByProductSubscriber($this->updater, $this->entityManager);
    }

    public function testItSubscribesToProductEvents(): void
    {
        $this->assertSame([
            'sylius.product_review.post_create' => 'onProductCreate',
            'sylius.product_review.post_update' => 'onProductUpdate',
        ], CalculateReviewCountByProductSubscriber::getSubscribedEvents());
    }

    public function testItUpdatesReviewCountOnProductCreateEvent(): void
    {
        $product = $this->createStub(ProductInterface::class);
        $this->updater->expects($this->once())->method('update')->with($product);
        $this->entityManager->expects($this->once())->method('flush');

        $this->subscriber->onProductCreate($this->createEvent($product));
    }

    public function testItUpdatesReviewCountOnProductUpdateEvent(): void
    {
        $product = $this->createStub(ProductInterface::class);
        $this->updater->expects($this->once())->method('update')->with($product);
        $this->entityManager->expects($this->once())->method('flush');

        $this->subscriber->onProductUpdate($this->createEvent($product));
    }

    private function createEvent(ProductInterface $product): GenericEvent
    {
        $productReview = $this->createStub(ProductReview::class);
        $productReview->method('getReviewSubject')->willReturn($product);

        return new GenericEvent($productReview);
    }
}
