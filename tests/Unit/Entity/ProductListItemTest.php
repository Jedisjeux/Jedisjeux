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
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ProductListItemTest extends TestCase
{
    private ProductListItem $item;

    protected function setUp(): void
    {
        $this->item = new ProductListItem();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->item);
    }

    public function testItHasNoListByDefault(): void
    {
        $this->assertNull($this->item->getList());
    }

    public function testItsListIsMutable(): void
    {
        $list = $this->createStub(ProductList::class);

        $this->item->setList($list);

        $this->assertSame($list, $this->item->getList());
    }

    public function testItHasNoProductByDefault(): void
    {
        $this->assertNull($this->item->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $product = $this->createStub(ProductInterface::class);

        $this->item->setProduct($product);

        $this->assertSame($product, $this->item->getProduct());
    }
}
