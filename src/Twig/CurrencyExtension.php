<?php

declare(strict_types=1);

namespace App\Twig;

use App\Templating\Helper\CurrencyHelperInterface;

final class CurrencyExtension extends \Twig_Extension
{
    /**
     * @var CurrencyHelperInterface
     */
    private $helper;

    /**
     * @param CurrencyHelperInterface $helper
     */
    public function __construct(CurrencyHelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter('sylius_currency_symbol', [$this->helper, 'convertCurrencyCodeToSymbol']),
        ];
    }
}
