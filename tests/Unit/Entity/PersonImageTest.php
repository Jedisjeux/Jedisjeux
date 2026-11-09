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
use App\Entity\Person;
use App\Entity\PersonImage;
use PHPUnit\Framework\TestCase;

final class PersonImageTest extends TestCase
{
    private PersonImage $sut;

    protected function setUp(): void
    {
        $this->sut = new PersonImage();
    }

    public function testExtendsAbstractImage(): void
    {
        $this->assertInstanceOf(AbstractImage::class, $this->sut);
    }

    public function testHasNoDescriptionByDefault(): void
    {
        $this->assertSame(null, $this->sut->getDescription());
    }

    public function testItsDescriptionIsMutable(): void
    {
        $this->sut->setDescription("Awesome");

        $this->assertSame("Awesome", $this->sut->getDescription());
    }

    public function testHasNoPersonByDefault(): void
    {
        $this->assertSame(null, $this->sut->getPerson());
    }

    public function testItsPersonIsMutable(): void
    {
        $obj = $this->createStub(Person::class);

        $this->sut->setPerson($obj);

        $this->assertSame($obj, $this->sut->getPerson());
    }

    public function testIsNotAMainImageByDefault(): void
    {
        $this->assertFalse($this->sut->isMain());
    }
}
