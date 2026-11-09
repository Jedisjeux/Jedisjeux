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
use App\Entity\FestivalListItem;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class FestivalListItemTest extends TestCase
{
    private FestivalListItem $sut;

    protected function setUp(): void
    {
        $this->sut = new FestivalListItem();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItsListIsMutable(): void
    {
        $list = $this->createStub(FestivalList::class);

        $this->sut->setList($list);
        $this->assertSame($list, $this->sut->getList());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->sut->setProduct($product);
        $this->assertSame($product, $this->sut->getProduct());
    }
}
