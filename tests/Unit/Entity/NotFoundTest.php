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

use App\Entity\NotFound;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;
use Zenstruck\RedirectBundle\Model\NotFound as BaseNotFound;

final class NotFoundTest extends TestCase
{
    private NotFound $sut;

    protected function setUp(): void
    {
        $this->sut = new NotFound('', '');
    }

    public function testImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->sut);
    }

    public function testExtendsBaseNotFound(): void
    {
        $this->assertInstanceOf(BaseNotFound::class, $this->sut);
    }

    public function testHasNoIdByDefault(): void
    {
        $this->assertSame(null, $this->sut->getId());
    }
}
