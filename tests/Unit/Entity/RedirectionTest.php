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

use App\Entity\Redirection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Model\ResourceInterface;
use Zenstruck\RedirectBundle\Model\Redirect as BaseRedirect;

final class RedirectionTest extends TestCase
{
    private Redirection $redirection;

    protected function setUp(): void
    {
        $this->redirection = new Redirection();
    }

    public function testItImplementsResourceInterface(): void
    {
        $this->assertInstanceOf(ResourceInterface::class, $this->redirection);
    }

    public function testItExtendsARedirectModel(): void
    {
        $this->assertInstanceOf(BaseRedirect::class, $this->redirection);
    }

    public function testItHasNoSourceByDefault(): void
    {
        $this->assertNull($this->redirection->getSource());
    }

    public function testItHasNoDestinationByDefault(): void
    {
        $this->assertNull($this->redirection->getDestination());
    }

    public function testItIsPermanentByDefault(): void
    {
        $this->assertTrue($this->redirection->isPermanent());
    }
}
