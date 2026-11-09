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
use App\Entity\Taxon;
use App\Event\ProductEvents;
use App\EventSubscriber\CalculateProductCountByTaxonSubscriber;
use App\Updater\ProductCountByTaxonUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\GenericEvent;

final class CalculateProductCountByTaxonSubscriberTest extends TestCase
{
    private ProductCountByTaxonUpdater&MockObject $updater;

    private CalculateProductCountByTaxonSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->updater = $this->createMock(ProductCountByTaxonUpdater::class);
        $this->subscriber = new CalculateProductCountByTaxonSubscriber($this->updater);
    }

    public function testItSubscribesToProductEvents(): void
    {
        $this->assertSame([
            ProductEvents::PRE_CREATE => 'onProductCreate',
            ProductEvents::PRE_UPDATE => 'onProductUpdate',
        ], CalculateProductCountByTaxonSubscriber::getSubscribedEvents());
    }

    public function testItUpdatesProductMainTaxonOnProductCreateEvent(): void
    {
        $taxon = $this->createStub(Taxon::class);

        $this->updater->expects($this->once())->method('update')->with($taxon);

        $this->subscriber->onProductCreate($this->createEvent($taxon, []));
    }

    public function testItUpdatesProductMainTaxonOnProductUpdateEvent(): void
    {
        $taxon = $this->createStub(Taxon::class);

        $this->updater->expects($this->once())->method('update')->with($taxon);

        $this->subscriber->onProductUpdate($this->createEvent($taxon, []));
    }

    public function testItUpdatesEachProductTaxonOnProductCreateEvent(): void
    {
        $taxon1 = $this->createStub(Taxon::class);
        $taxon2 = $this->createStub(Taxon::class);

        $updatedTaxons = [];
        $this->updater->expects($this->exactly(2))->method('update')->willReturnCallback(
            static function (Taxon $taxon) use (&$updatedTaxons): void {
                $updatedTaxons[] = $taxon;
            },
        );

        $this->subscriber->onProductCreate($this->createEvent(null, [$taxon1, $taxon2]));

        $this->assertSame([$taxon1, $taxon2], $updatedTaxons);
    }

    public function testItUpdatesEachProductTaxonOnProductUpdateEvent(): void
    {
        $taxon1 = $this->createStub(Taxon::class);
        $taxon2 = $this->createStub(Taxon::class);

        $updatedTaxons = [];
        $this->updater->expects($this->exactly(2))->method('update')->willReturnCallback(
            static function (Taxon $taxon) use (&$updatedTaxons): void {
                $updatedTaxons[] = $taxon;
            },
        );

        $this->subscriber->onProductUpdate($this->createEvent(null, [$taxon1, $taxon2]));

        $this->assertSame([$taxon1, $taxon2], $updatedTaxons);
    }

    /**
     * @param list<Taxon> $taxons
     */
    private function createEvent(?Taxon $mainTaxon, array $taxons): GenericEvent
    {
        $product = $this->createStub(Product::class);
        $product->method('getMainTaxon')->willReturn($mainTaxon);
        $product->method('getTaxons')->willReturn(new ArrayCollection($taxons));

        return new GenericEvent($product);
    }
}
