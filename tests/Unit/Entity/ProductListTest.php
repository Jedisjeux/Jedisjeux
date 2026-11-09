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

use App\Entity\ProductList;
use App\Entity\ProductListItem;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ProductListTest extends TestCase
{
    private ProductList $list;

    protected function setUp(): void
    {
        $this->list = new ProductList();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->list);
    }

    public function testItHasAGeneratedCodeByDefault(): void
    {
        $this->assertNotNull($this->list->getCode());
    }

    public function testItsCodeIsMutable(): void
    {
        $this->list->setCode(ProductList::CODE_GAME_LIBRARY);

        $this->assertSame(ProductList::CODE_GAME_LIBRARY, $this->list->getCode());
    }

    public function testItHasNoNameByDefault(): void
    {
        $this->assertNull($this->list->getName());
    }

    public function testItsNameIsMutable(): void
    {
        $this->list->setName('My awesome list');

        $this->assertSame('My awesome list', $this->list->getName());
    }

    public function testItHasNoOwnerByDefault(): void
    {
        $this->assertNull($this->list->getOwner());
    }

    public function testItsOwnerIsMutable(): void
    {
        $owner = $this->createStub(CustomerInterface::class);

        $this->list->setOwner($owner);

        $this->assertSame($owner, $this->list->getOwner());
    }

    public function testItInitializesItemsCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->list->getItems());
    }

    public function testItAddsItem(): void
    {
        $item = $this->createMock(ProductListItem::class);
        $item->expects($this->once())->method('setList')->with($this->list);

        $this->list->addItem($item);

        $this->assertTrue($this->list->hasItem($item));
    }

    public function testItRemovesItem(): void
    {
        $item = $this->createStub(ProductListItem::class);

        $this->list->addItem($item);
        $this->list->removeItem($item);

        $this->assertFalse($this->list->hasItem($item));
    }
}
