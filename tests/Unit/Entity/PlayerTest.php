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
use App\Entity\Player;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class PlayerTest extends TestCase
{
    private Player $sut;

    protected function setUp(): void
    {
        $this->sut = new Player();
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testHasNoScoreByDefault(): void
    {
        $this->assertSame(null, $this->sut->getScore());
    }

    public function testItsScoreIsMutable(): void
    {
        $this->sut->setScore(666.0);

        $this->assertSame(666.0, $this->sut->getScore());
    }

    public function testHasNoNameByDefault(): void
    {
        $this->assertSame(null, $this->sut->getName());
    }

    public function testItsNameIsMutable(): void
    {
        $this->sut->setName("John Snow");

        $this->assertSame("John Snow", $this->sut->getName());
    }

    public function testHasNoGamePlayByDefault(): void
    {
        $this->assertSame(null, $this->sut->getGamePlay());
    }

    public function testItsGamePlayIsMutable(): void
    {
        $obj = $this->createStub(GamePlay::class);

        $this->sut->setGamePlay($obj);

        $this->assertSame($obj, $this->sut->getGamePlay());
    }

    public function testHasNoCustomerByDefault(): void
    {
        $this->assertSame(null, $this->sut->getCustomer());
    }

    public function testItsCustomerIsMutable(): void
    {
        $obj = $this->createStub(CustomerInterface::class);

        $this->sut->setCustomer($obj);

        $this->assertSame($obj, $this->sut->getCustomer());
    }
}
