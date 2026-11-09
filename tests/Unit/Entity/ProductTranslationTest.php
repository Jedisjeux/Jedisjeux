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

use App\Entity\ProductTranslation;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Model\ProductTranslation as BaseProductTranslation;

final class ProductTranslationTest extends TestCase
{
    private ProductTranslation $translation;

    protected function setUp(): void
    {
        $this->translation = new ProductTranslation();
    }

    public function testItExtendsAProductTranslationModel(): void
    {
        $this->assertInstanceOf(BaseProductTranslation::class, $this->translation);
    }

    public function testItHasNoShortDescriptionByDefault(): void
    {
        $this->assertNull($this->translation->getShortDescription());
    }

    public function testItsShortDescriptionIsMutable(): void
    {
        $this->translation->setShortDescription('Winter is coming...');

        $this->assertSame('Winter is coming...', $this->translation->getShortDescription());
    }
}
