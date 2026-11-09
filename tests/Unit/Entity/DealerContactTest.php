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

use App\Entity\Dealer;
use App\Entity\DealerContact;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class DealerContactTest extends TestCase
{
    private DealerContact $sut;

    protected function setUp(): void
    {
        $this->sut = new DealerContact();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItsEmailIsMutable(): void
    {
        $this->sut->setEmail('jon.snow@example.com');
        $this->assertSame('jon.snow@example.com', $this->sut->getEmail());
    }

    public function testItsFirstNameIsMutable(): void
    {
        $this->sut->setFirstName('Jon');
        $this->assertSame('Jon', $this->sut->getFirstName());
    }

    public function testItsLastNameIsMutable(): void
    {
        $this->sut->setLastName('Snow');
        $this->assertSame('Snow', $this->sut->getLastName());
    }

    public function testItsDealerIsMutable(): void
    {
        $dealer = $this->createStub(Dealer::class);

        $this->sut->setDealer($dealer);
        $this->assertSame($dealer, $this->sut->getDealer());
    }
}
