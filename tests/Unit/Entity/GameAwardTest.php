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

use App\Entity\GameAward;
use App\Entity\GameAwardImage;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class GameAwardTest extends TestCase
{
    private GameAward $sut;

    protected function setUp(): void
    {
        $this->sut = new GameAward();
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testHasNoIdByDefault(): void
    {
        $this->assertSame(null, $this->sut->getId());
    }

    public function testHasNoNameByDefault(): void
    {
        $this->assertSame(null, $this->sut->getName());
    }

    public function testItsNameIsMutable(): void
    {
        $this->sut->setName('Spiel des Jahres');

        $this->assertSame('Spiel des Jahres', $this->sut->getName());
    }

    public function testHasNoSlugByDefault(): void
    {
        $this->assertSame(null, $this->sut->getSlug());
    }

    public function testItsSlugIsMutable(): void
    {
        $this->sut->setSlug('spiel-des-jahres');

        $this->assertSame('spiel-des-jahres', $this->sut->getSlug());
    }

    public function testHasNoDescriptionByDefault(): void
    {
        $this->assertSame(null, $this->sut->getDescription());
    }

    public function testItsDescriptionIsMutable(): void
    {
        $this->sut->setDescription('Howard the duck');

        $this->assertSame('Howard the duck', $this->sut->getDescription());
    }

    public function testHasNoImageByDefault(): void
    {
        $this->assertSame(null, $this->sut->getImage());
    }

    public function testItsImageIsMutable(): void
    {
        $image = $this->createStub(GameAwardImage::class);

        $this->sut->setImage($image);

        $this->assertSame($image, $this->sut->getImage());
    }
}
