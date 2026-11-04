<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\UX\Icons\IconRendererInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Renders Font Awesome 4 class names (taxon icon classes stored in database, macros) as UX Icons of the "fa" Iconify set,
 * inside the <i> element of the former icon font. Icons rendered this way are not found by ux:icons:lock, import them with ux:icons:import.
 */
final class FontAwesomeIconExtension extends AbstractExtension
{
    private const MODIFIER_PATTERN = '/^fa-(lg|\d+x|fw|li|ul|border|spin|pulse|inverse|stack(-\dx)?|pull-(left|right)|rotate-\d+|flip-(horizontal|vertical))$/';

    public function __construct(
        private readonly IconRendererInterface $iconRenderer,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fa_icon', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $classes Font Awesome classes, e.g. "fa fa-star text-default"; the last fa-* icon class wins
     */
    public function render(?string $classes, array $attributes = []): string
    {
        $classes = trim((string) $classes);
        $iconNames = array_values(array_filter(
            explode(' ', $classes),
            static fn (string $class): bool => str_starts_with($class, 'fa-') && !preg_match(self::MODIFIER_PATTERN, $class),
        ));

        if ([] === $iconNames) {
            return '';
        }

        $class = trim($classes.' '.($attributes['class'] ?? ''));

        // The former <i> element keeps the theme styles of the icons (sizes, colors, circles)
        return sprintf(
            '<i class="%s" aria-hidden="true">%s</i>',
            htmlspecialchars($class, \ENT_QUOTES),
            $this->iconRenderer->renderIcon('fa:'.substr(end($iconNames), 3), ['class' => 'fa-svg']),
        );
    }
}
