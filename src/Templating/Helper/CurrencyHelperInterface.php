<?php

declare(strict_types=1);

namespace App\Templating\Helper;

interface CurrencyHelperInterface
{
    /**
     * @param string $code
     *
     * @return string
     */
    public function convertCurrencyCodeToSymbol(string $code): string;
}
