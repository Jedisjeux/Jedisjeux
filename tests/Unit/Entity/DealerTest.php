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

namespace App\Tests\Unit\Entity;

use App\Entity\Dealer;
use App\Entity\DealerContact;
use App\Entity\DealerImage;
use App\Entity\PriceList;
use App\Entity\PubBanner;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class DealerTest extends TestCase
{
    private Dealer $sut;

    protected function setUp(): void
    {
        $this->sut = new Dealer();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItHasNoCodeByDefault(): void
    {
        $this->assertNull($this->sut->getCode());
    }

    public function testItsCodeIsMutable(): void
    {
        $this->sut->setCode("philibert");
        $this->assertSame("philibert", $this->sut->getCode());
    }

    public function testItHasNoNameByDefault(): void
    {
        $this->assertNull($this->sut->getName());
    }

    public function testItsNameIsMutable(): void
    {
        $this->sut->setName("Philibert");
        $this->assertSame("Philibert", $this->sut->getName());
    }

    public function testItHasNoImageByDefault(): void
    {
        $this->assertNull($this->sut->getImage());
    }

    public function testItsImageIsMutable(): void
    {
        $image = $this->createStub(DealerImage::class);

        $this->sut->setImage($image);
        $this->assertSame($image, $this->sut->getImage());
    }

    public function testItHasNoPriceListByDefault(): void
    {
        $this->assertNull($this->sut->getPriceList());
        $this->assertFalse($this->sut->hasPriceList());
    }

    public function testItsPriceListIsMutable(): void
    {
        $priceList = $this->createMock(PriceList::class);
        $priceList->expects($this->once())->method('setDealer')->with($this->sut);

        $this->sut->setPriceList($priceList);
        $this->assertSame($priceList, $this->sut->getPriceList());
    }

    public function testItInitializesPubBannersCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getPubBanners());
    }

    public function testItAddsPubBanner(): void
    {
        $pubBanner = $this->createMock(PubBanner::class);
        $pubBanner->expects($this->once())->method('setDealer')->with($this->sut);

        $this->sut->addPubBanner($pubBanner);
        $this->assertTrue($this->sut->hasPubBanner($pubBanner));
    }

    public function testItRemovesPubBanner(): void
    {
        $dealers = [];
        $pubBanner = $this->createMock(PubBanner::class);
        $pubBanner->expects($this->exactly(2))->method('setDealer')->willReturnCallback(function ($dealer) use (&$dealers, $pubBanner) {
            $dealers[] = $dealer;

            return $pubBanner;
        });

        $this->sut->addPubBanner($pubBanner);
        $this->sut->removePubBanner($pubBanner);

        $this->assertSame([$this->sut, null], $dealers);
        $this->assertFalse($this->sut->hasPubBanner($pubBanner));
    }

    public function testItInitializesContactsCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getContacts());
    }

    public function testItAddsContact(): void
    {
        $contact = $this->createMock(DealerContact::class);
        $contact->expects($this->once())->method('setDealer')->with($this->sut);

        $this->sut->addContact($contact);
        $this->assertTrue($this->sut->hasContact($contact));
    }

    public function testItRemovesContact(): void
    {
        $dealers = [];
        $contact = $this->createMock(DealerContact::class);
        $contact->expects($this->exactly(2))->method('setDealer')->willReturnCallback(function ($dealer) use (&$dealers, $contact) {
            $dealers[] = $dealer;

            return $contact;
        });

        $this->sut->addContact($contact);
        $this->sut->removeContact($contact);

        $this->assertSame([$this->sut, null], $dealers);
        $this->assertFalse($this->sut->hasContact($contact));
    }

    public function testItsStringConversionReturnsAnEmptyStringWhenItHasNoName(): void
    {
        $this->assertSame('', (string) $this->sut);
    }

    public function testItsStringConversionReturnsItsName(): void
    {
        $this->sut->setName('Philibert');
        $this->assertSame('Philibert', (string) $this->sut);
    }
}
