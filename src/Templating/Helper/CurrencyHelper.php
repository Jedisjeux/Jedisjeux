<?php

declare(strict_types=1);

namespace App\Templating\Helper;

use Symfony\Component\Intl\Currencies;
use Symfony\Component\Templating\Helper\Helper;

class CurrencyHelper extends Helper implements CurrencyHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function convertCurrencyCodeToSymbol(string $code): string
    {
        return Currencies::getSymbol($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'sylius_currency';
    }
}
