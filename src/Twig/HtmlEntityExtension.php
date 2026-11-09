<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class HtmlEntityExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('html_entity_decode', [$this, 'decode']),
        ];
    }

    public function decode(?string $value): string
    {
        return html_entity_decode((string) $value, \ENT_QUOTES | \ENT_HTML5, 'UTF-8');
    }
}
