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

namespace App\Tests\Unit\Updater;

use App\Updater\AddProductToAssociatedProductsUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AddProductToAssociatedProductsUpdaterTest extends TestCase
{
    private AddProductToAssociatedProductsUpdater $updater;

    protected function setUp(): void
    {
        $this->updater = new AddProductToAssociatedProductsUpdater($this->createStub(FactoryInterface::class));
    }

    public function testItAddsAProductToEachAssociatedProducts(): void
    {
        $product = $this->createStub(ProductInterface::class);
        $product->method('getId')->willReturn(1);

        $associationType = $this->createStub(ProductAssociationTypeInterface::class);
        $associationType->method('getCode')->willReturn('XYZ');

        $associatedProduct = $this->createStub(ProductInterface::class);
        $associatedProduct->method('getId')->willReturn(2);

        $association = $this->createStub(ProductAssociationInterface::class);
        $association->method('getType')->willReturn($associationType);
        $association->method('getAssociatedProducts')->willReturn(new ArrayCollection([$associatedProduct]));

        $associatedProductAssociationType = $this->createStub(ProductAssociationTypeInterface::class);
        $associatedProductAssociationType->method('getCode')->willReturn('XYZ');

        $associatedProductAssociation = $this->createMock(ProductAssociationInterface::class);
        $associatedProductAssociation->method('getType')->willReturn($associatedProductAssociationType);
        $associatedProduct->method('getAssociations')->willReturn(new ArrayCollection([$associatedProductAssociation]));

        $associatedProductAssociation->expects($this->once())->method('addAssociatedProduct')->with($product);

        $this->updater->update($product, $association);
    }

    public function testItSkipsAssociatedProductIfItIsTheCurrentProduct(): void
    {
        $product = $this->createStub(ProductInterface::class);
        $product->method('getId')->willReturn(1);

        $associationType = $this->createStub(ProductAssociationTypeInterface::class);
        $associationType->method('getCode')->willReturn('XYZ');

        $associatedProduct = $this->createStub(ProductInterface::class);
        $associatedProduct->method('getId')->willReturn(1);

        $association = $this->createStub(ProductAssociationInterface::class);
        $association->method('getType')->willReturn($associationType);
        $association->method('getAssociatedProducts')->willReturn(new ArrayCollection([$associatedProduct]));

        $associatedProductAssociationType = $this->createStub(ProductAssociationTypeInterface::class);
        $associatedProductAssociationType->method('getCode')->willReturn('XYZ');

        $associatedProductAssociation = $this->createMock(ProductAssociationInterface::class);
        $associatedProductAssociation->method('getType')->willReturn($associatedProductAssociationType);
        $associatedProduct->method('getAssociations')->willReturn(new ArrayCollection([$associatedProductAssociation]));

        $associatedProductAssociation->expects($this->never())->method('addAssociatedProduct');

        $this->updater->update($product, $association);
    }
}
