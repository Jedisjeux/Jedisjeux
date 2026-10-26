<?php

declare(strict_types=1);

namespace App\Twig;

use App\Templating\Helper\CurrencyHelperInterface;

final class CurrencyExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @var CurrencyHelperInterface
     */
    private $helper;

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
            new \Twig\TwigFilter('sylius_currency_symbol', [$this->helper, 'convertCurrencyCodeToSymbol']),
        ];
    }
}
