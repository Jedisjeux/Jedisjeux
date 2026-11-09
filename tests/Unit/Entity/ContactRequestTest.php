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

use App\Entity\ContactRequest;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ContactRequestTest extends TestCase
{
    private ContactRequest $sut;

    protected function setUp(): void
    {
        $this->sut = new ContactRequest();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testItHasNoFirstNameByDefault(): void
    {
        $this->assertNull($this->sut->getFirstName());
    }

    public function testItsFirstNameIsMutable(): void
    {
        $this->sut->setFirstName('John');
        $this->assertSame('John', $this->sut->getFirstName());
    }

    public function testItHasNoLastNameByDefault(): void
    {
        $this->assertNull($this->sut->getLastName());
    }

    public function testItsLastNameIsMutable(): void
    {
        $this->sut->setLastName('Nathan');
        $this->assertSame('Nathan', $this->sut->getLastName());
    }

    public function testItHasNoFullNameByDefault(): void
    {
        $this->assertSame('', $this->sut->getFullName());
    }

    public function testItCanGetFullName(): void
    {
        $this->sut->setFirstName('Marty');
        $this->sut->setLastName('McFly');
        $this->assertSame('Marty McFly', $this->sut->getFullName());
    }

    public function testItHasNoEmailByDefault(): void
    {
        $this->assertNull($this->sut->getEmail());
    }

    public function testItsEmailIsMutable(): void
    {
        $this->sut->setEmail('john.nathan@example.com');
        $this->assertSame('john.nathan@example.com', $this->sut->getEmail());
    }

    public function testItHasNoBodyByDefault(): void
    {
        $this->assertNull($this->sut->getBody());
    }

    public function testItsBodyIsMutable(): void
    {
        $this->sut->setBody('<p>Contact Request body</p>');
        $this->assertSame('<p>Contact Request body</p>', $this->sut->getBody());
    }
}
