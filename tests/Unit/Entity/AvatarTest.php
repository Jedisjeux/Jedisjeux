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
use App\Entity\Avatar;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class AvatarTest extends TestCase
{
    private Avatar $sut;

    protected function setUp(): void
    {
        $this->sut = new Avatar();
    }

    public function testItExtendsAbstractImage(): void
    {
        $this->assertInstanceOf(AbstractImage::class, $this->sut);
    }

    public function testItsUploadDirIsMovedOnAnAvatarDirectory(): void
    {
        $this->sut->setPath('image.jpg');
        $this->assertStringContainsString('avatar', $this->sut->getAbsolutePath());
    }

    public function testItsPathIsMutable(): void
    {
        $this->sut->setPath('image.jpg');
        $this->assertSame('image.jpg', $this->sut->getPath());
    }

    public function testItsFileIsMutable(): void
    {
        $file = new UploadedFile(__FILE__, basename(__FILE__));

        $this->sut->setFile($file);
        $this->assertSame($file, $this->sut->getFile());
    }
}
