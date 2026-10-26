<?php

declare(strict_types=1);

namespace App\Templating\Helper;

interface CurrencyHelperInterface
{
    public function convertCurrencyCodeToSymbol(string $code): string;
}
