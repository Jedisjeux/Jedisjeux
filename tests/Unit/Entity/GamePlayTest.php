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

use App\Entity\GamePlay;
use App\Entity\GamePlayImage;
use App\Entity\Player;
use App\Entity\Topic;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class GamePlayTest extends TestCase
{
    private GamePlay $sut;

    protected function setUp(): void
    {
        $this->sut = new GamePlay();
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItsCodeIsMutable(): void
    {
        $this->sut->setCode('GAME_PLAY1');

        $this->assertSame('GAME_PLAY1', $this->sut->getCode());
    }

    public function testHasNoDurationByDefault(): void
    {
        $this->assertSame(null, $this->sut->getDuration());
    }

    public function testItsDurationIsMutable(): void
    {
        $this->sut->setDuration(30);

        $this->assertSame(30, $this->sut->getDuration());
    }

    public function testHasNoPlayerCountByDefault(): void
    {
        $this->assertSame(null, $this->sut->getPlayerCount());
    }

    public function testItsPlayerCountIsMutable(): void
    {
        $this->sut->setPlayerCount(4);

        $this->assertSame(4, $this->sut->getPlayerCount());
    }

    public function testItsPlayedAtTodayByDefault(): void
    {
        $today = new \DateTime('today');

        $this->assertInstanceOf(\DateTime::class, $this->sut->getPlayedAt());
        $this->assertSame($today->format('Y-m-d'), $this->sut->getPlayedAt()->format('Y-m-d'));
    }

    public function testHasNoProductByDefault(): void
    {
        $this->assertSame(null, $this->sut->getProduct());
    }

    public function testItsProductIsMutable(): void
    {
        $obj = $this->createStub(ProductInterface::class);

        $this->sut->setProduct($obj);

        $this->assertSame($obj, $this->sut->getProduct());
    }

    public function testHasNoAuthorByDefault(): void
    {
        $this->assertSame(null, $this->sut->getAuthor());
    }

    public function testItsAuthorIsMutable(): void
    {
        $obj = $this->createStub(CustomerInterface::class);

        $this->sut->setAuthor($obj);

        $this->assertSame($obj, $this->sut->getAuthor());
    }

    public function testHasNoTopicByDefault(): void
    {
        $this->assertSame(null, $this->sut->getTopic());
    }

    public function testItsTopicIsMutable(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getGamePlay')->willReturn(null);
        $topic->expects($this->once())->method('setGamePlay')->with($this->sut);

        $this->sut->setTopic($topic);

        $this->assertSame($topic, $this->sut->getTopic());
    }

    public function testInitializesImagesCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getImages());
    }

    public function testAddsImage(): void
    {
        $image = $this->createMock(GamePlayImage::class);
        $image->expects($this->once())->method('setGamePlay')->with($this->sut);

        $this->sut->addImage($image);

        $this->assertTrue($this->sut->hasImage($image));
    }

    public function testRemovesImage(): void
    {
        $image = $this->createStub(GamePlayImage::class);

        $this->sut->addImage($image);
        $this->sut->removeImage($image);

        $this->assertFalse($this->sut->hasImage($image));
    }

    public function testItsImageCountIsMutable(): void
    {
        $this->sut->setImageCount(2);

        $this->assertSame(2, $this->sut->getImageCount());
    }

    public function testInitializesPlayersCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->sut->getPlayers());
    }

    public function testAddsPlayer(): void
    {
        $player = $this->createMock(Player::class);
        $player->expects($this->once())->method('setGamePlay')->with($this->sut);

        $this->sut->addPlayer($player);

        $this->assertTrue($this->sut->hasPlayer($player));
    }

    public function testRemovesPlayer(): void
    {
        $player = $this->createStub(Player::class);

        $this->sut->addPlayer($player);
        $this->sut->removePlayer($player);

        $this->assertFalse($this->sut->hasPlayer($player));
    }
}
