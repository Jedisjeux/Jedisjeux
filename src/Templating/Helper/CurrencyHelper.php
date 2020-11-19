<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Templating\Helper;

use Symfony\Component\Intl\Intl;
use Symfony\Component\Templating\Helper\Helper;

class CurrencyHelper extends Helper implements CurrencyHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function convertCurrencyCodeToSymbol(string $code): string
    {
        return Intl::getCurrencyBundle()->getCurrencySymbol($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'sylius_currency';
    }
}
