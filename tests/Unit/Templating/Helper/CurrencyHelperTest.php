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

namespace App\Tests\Unit\Templating\Helper;

use App\Templating\Helper\CurrencyHelper;
use App\Templating\Helper\CurrencyHelperInterface;
use PHPUnit\Framework\TestCase;

final class CurrencyHelperTest extends TestCase
{
    private CurrencyHelper $helper;

    protected function setUp(): void
    {
        $this->helper = new CurrencyHelper();
    }

    public function testItImplementsACurrencyHelperInterface(): void
    {
        $this->assertInstanceOf(CurrencyHelperInterface::class, $this->helper);
    }

    public function testItTransformsACurrencyCodeIntoSymbol(): void
    {
        $this->assertSame('$', $this->helper->convertCurrencyCodeToSymbol('USD'));
    }
}
