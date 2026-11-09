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

use App\Entity\Person;
use App\Entity\Product;
use App\Event\ProductEvents;
use App\EventSubscriber\CalculateProductCountByPersonSubscriber;
use App\Updater\ProductCountByPersonUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CalculateProductCountByPersonSubscriberTest extends TestCase
{
    private ProductCountByPersonUpdater&MockObject $updater;

    private CalculateProductCountByPersonSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->updater = $this->createMock(ProductCountByPersonUpdater::class);
        $this->subscriber = new CalculateProductCountByPersonSubscriber($this->updater);
    }

    public function testItSubscribesToPostCreateEvent(): void
    {
        $this->assertSame([
            ProductEvents::PRE_CREATE => 'onProductCreate',
            ProductEvents::PRE_UPDATE => 'onProductUpdate',
        ], CalculateProductCountByPersonSubscriber::getSubscribedEvents());
    }

    public function testItUpdatesForEachDesignerOnProductCreateEvent(): void
    {
        [$designer1, $designer2] = $this->expectUpdatesOfTwoPersons();

        $this->subscriber->onProductCreate($this->createEvent([$designer1, $designer2], [], []));

        $this->assertUpdated([$designer1, $designer2]);
    }

    public function testItUpdatesForEachDesignerOnProductUpdateEvent(): void
    {
        [$designer1, $designer2] = $this->expectUpdatesOfTwoPersons();

        $this->subscriber->onProductUpdate($this->createEvent([$designer1, $designer2], [], []));

        $this->assertUpdated([$designer1, $designer2]);
    }

    public function testItUpdatesForEachArtistOnProductCreateEvent(): void
    {
        [$artist1, $artist2] = $this->expectUpdatesOfTwoPersons();

        $this->subscriber->onProductCreate($this->createEvent([], [$artist1, $artist2], []));

        $this->assertUpdated([$artist1, $artist2]);
    }

    public function testItUpdatesForEachArtistOnProductUpdateEvent(): void
    {
        [$artist1, $artist2] = $this->expectUpdatesOfTwoPersons();

        $this->subscriber->onProductUpdate($this->createEvent([], [$artist1, $artist2], []));

        $this->assertUpdated([$artist1, $artist2]);
    }

    public function testItUpdatesForEachPublisherOnProductCreateEvent(): void
    {
        [$publisher1, $publisher2] = $this->expectUpdatesOfTwoPersons();

        $this->subscriber->onProductCreate($this->createEvent([], [], [$publisher1, $publisher2]));

        $this->assertUpdated([$publisher1, $publisher2]);
    }

    public function testItUpdatesForEachPublisherOnProductUpdateEvent(): void
    {
        [$publisher1, $publisher2] = $this->expectUpdatesOfTwoPersons();

        $this->subscriber->onProductUpdate($this->createEvent([], [], [$publisher1, $publisher2]));

        $this->assertUpdated([$publisher1, $publisher2]);
    }

    /** @var list<Person> */
    private array $updatedPersons = [];

    /**
     * @return array{Person, Person}
     */
    private function expectUpdatesOfTwoPersons(): array
    {
        $this->updatedPersons = [];
        $this->updater->expects($this->exactly(2))->method('update')->willReturnCallback(
            function (Person $person): void {
                $this->updatedPersons[] = $person;
            },
        );

        return [$this->createStub(Person::class), $this->createStub(Person::class)];
    }

    /**
     * @param list<Person> $expected
     */
    private function assertUpdated(array $expected): void
    {
        $this->assertSame($expected, $this->updatedPersons);
    }

    /**
     * @param list<Person> $designers
     * @param list<Person> $artists
     * @param list<Person> $publishers
     */
    private function createEvent(array $designers, array $artists, array $publishers): GenericEvent
    {
        $product = $this->createStub(Product::class);
        $product->method('getDesigners')->willReturn(new ArrayCollection($designers));
        $product->method('getArtists')->willReturn(new ArrayCollection($artists));
        $product->method('getPublishers')->willReturn(new ArrayCollection($publishers));

        return new GenericEvent($product);
    }
}
