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

use App\Entity\GamePlay;
use App\EventSubscriber\CalculateImageCountByGamePlaySubscriber;
use App\Updater\ImageCountByGamePlayUpdater;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CalculateImageCountByGamePlaySubscriberTest extends TestCase
{
    private ImageCountByGamePlayUpdater&MockObject $updater;

    private CalculateImageCountByGamePlaySubscriber $subscriber;

    protected function setUp(): void
    {
        $this->updater = $this->createMock(ImageCountByGamePlayUpdater::class);
        $this->subscriber = new CalculateImageCountByGamePlaySubscriber($this->updater);
    }

    public function testItSubscribesToDoctrineEvents(): void
    {
        $this->assertSame([
            Events::prePersist,
            Events::preUpdate,
        ], $this->subscriber->getSubscribedEvents());
    }

    public function testItUpdatesImageCountOnPrePersistEvent(): void
    {
        $gamePlay = $this->createStub(GamePlay::class);
        $this->updater->expects($this->once())->method('update')->with($gamePlay);

        $this->subscriber->prePersist($this->createArgs($gamePlay));
    }

    public function testItUpdatesImageCountOnPreUpdateEvent(): void
    {
        $gamePlay = $this->createStub(GamePlay::class);
        $this->updater->expects($this->once())->method('update')->with($gamePlay);

        $this->subscriber->preUpdate($this->createArgs($gamePlay));
    }

    public function testItDoesNothingWhenArgsObjectIsNotGamePlayType(): void
    {
        $this->updater->expects($this->never())->method('update');

        $this->subscriber->updateImageCount($this->createArgs(new \stdClass()));
    }

    private function createArgs(object $object): LifecycleEventArgs
    {
        $args = $this->createStub(LifecycleEventArgs::class);
        $args->method('getObject')->willReturn($object);

        return $args;
    }
}
