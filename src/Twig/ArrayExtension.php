<?php

declare(strict_types=1);

namespace App\Twig;

/**
 * Replaces the shuffle filter of the abandoned twig/extensions package.
 */
final class ArrayExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('shuffle', [$this, 'shuffle']),
        ];
    }

    public function shuffle(iterable $items): array
    {
        $items = is_array($items) ? $items : iterator_to_array($items);
        shuffle($items);

        return $items;
    }
}
