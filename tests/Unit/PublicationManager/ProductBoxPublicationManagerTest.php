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

namespace App\Tests\Unit\PublicationManager;

use App\Entity\ProductBox;
use App\Entity\ProductVariantInterface;
use App\PublicationManager\ProductBoxPublicationManager;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

final class ProductBoxPublicationManagerTest extends TestCase
{
    public function testItEnablesAProductBox(): void
    {
        $variant = $this->createStub(ProductVariantInterface::class);
        $box = $this->createMock(ProductBox::class);
        $objectManager = $this->createMock(ObjectManager::class);
        $box->method('getProductVariant')->willReturn($variant);

        $box->expects($this->once())->method('enable');
        $objectManager->expects($this->once())->method('flush');

        (new ProductBoxPublicationManager($objectManager))->enable($box);
    }

    public function testItDisablesPreviousEnabledBox(): void
    {
        $variant = $this->createStub(ProductVariantInterface::class);
        $previousBox = $this->createMock(ProductBox::class);
        $box = $this->createMock(ProductBox::class);
        $box->method('getProductVariant')->willReturn($variant);
        $variant->method('getEnabledBox')->willReturn($previousBox);

        $previousBox->expects($this->once())->method('disable');
        $box->expects($this->once())->method('enable');

        (new ProductBoxPublicationManager($this->createStub(ObjectManager::class)))->enable($box);
    }

    public function testItThrowsAnInvalidArgumentExceptionIfBoxHasNoProductVariant(): void
    {
        $box = $this->createStub(ProductBox::class);
        $box->method('getProductVariant')->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);

        (new ProductBoxPublicationManager($this->createStub(ObjectManager::class)))->enable($box);
    }
}
