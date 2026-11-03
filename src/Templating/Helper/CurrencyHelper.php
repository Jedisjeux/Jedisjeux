<?php

declare(strict_types=1);

namespace App\Templating\Helper;

use Symfony\Component\Intl\Currencies;

class CurrencyHelper implements CurrencyHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function convertCurrencyCodeToSymbol(string $code): string
    {
        return Currencies::getSymbol($code);
    }
}
