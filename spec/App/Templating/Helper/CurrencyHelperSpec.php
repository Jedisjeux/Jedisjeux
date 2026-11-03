<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\App\Templating\Helper;

use PhpSpec\ObjectBehavior;
use App\Templating\Helper\CurrencyHelperInterface;

final class CurrencyHelperSpec extends ObjectBehavior
{
    function it_implements_a_currency_helper_interface(): void
    {
        $this->shouldImplement(CurrencyHelperInterface::class);
    }

    function it_transforms_a_currency_code_into_symbol(): void
    {
        $this->convertCurrencyCodeToSymbol('USD')->shouldReturn('$');
    }
}
