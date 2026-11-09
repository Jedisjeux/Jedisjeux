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

use App\Entity\FestivalList;
use App\Entity\FestivalListImage;
use App\Entity\FestivalListItem;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class FestivalListTest extends TestCase
{
    private FestivalList $sut;

    protected function setUp(): void
    {
        $this->sut = new FestivalList();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItSetsCode(): void
    {
        $this->sut->setCode('staff_list');
        $this->assertSame('staff_list', $this->sut->getCode());
    }

    public function testItSetsName(): void
    {
        $this->sut->setName('Awesome name');
        $this->assertSame('Awesome name', $this->sut->getName());
    }

    public function testItSetsSlug(): void
    {
        $this->sut->setSlug('awesome-name');
        $this->assertSame('awesome-name', $this->sut->getSlug());
    }

    public function testItSetsDescription(): void
    {
        $this->sut->setName('What an awesome description');
        $this->assertSame('What an awesome description', $this->sut->getName());
    }

    public function testItSetsStartAt(): void
    {
        $startAt = new \DateTime();

        $this->sut->setStartAt($startAt);
        $this->assertSame($startAt, $this->sut->getStartAt());
    }

    public function testItSetsEndAt(): void
    {
        $endAt = new \DateTime();

        $this->sut->setEndAt($endAt);
        $this->assertSame($endAt, $this->sut->getEndAt());
    }

    public function testItHasNoImageByDefault(): void
    {
        $this->assertNull($this->sut->getImage());
    }

    public function testItsImageIsMutable(): void
    {
        $image = $this->createStub(FestivalListImage::class);

        $this->sut->setImage($image);
        $this->assertSame($image, $this->sut->getImage());
    }

    public function testItsProductsIsCollection(): void
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->sut->getItems());
    }

    public function testItCanAddItems(): void
    {
        $item = $this->createStub(FestivalListItem::class);

        $this->sut->addItem($item);
        $this->assertTrue($this->sut->hasItem($item));
    }

    public function testItCanRemoveItems(): void
    {
        $item = $this->createStub(FestivalListItem::class);

        $this->sut->addItem($item);
        $this->sut->removeItem($item);
        $this->assertFalse($this->sut->hasItem($item));
    }
}
