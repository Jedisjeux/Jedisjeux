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

use App\Entity\AbstractImage;
use App\Entity\ArticleImage;
use PHPUnit\Framework\TestCase;

final class ArticleImageTest extends TestCase
{
    private ArticleImage $sut;

    protected function setUp(): void
    {
        $this->sut = new ArticleImage();
    }

    public function testItExtendsAbstractImage(): void
    {
        $this->assertInstanceOf(AbstractImage::class, $this->sut);
    }
}
