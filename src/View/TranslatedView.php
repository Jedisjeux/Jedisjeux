<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\View;

use Pagerfanta\PagerfantaInterface;
use Pagerfanta\View\ViewInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Decorates a view to translate its previous and next messages.
 */
abstract class TranslatedView implements ViewInterface
{
    private $view;
    private $translator;

    public function __construct(ViewInterface $view, TranslatorInterface $translator)
    {
        $this->view = $view;
        $this->translator = $translator;
    }

    public function render(PagerfantaInterface $pagerfanta, callable $routeGenerator, array $options = []): string
    {
        $options['prev_message'] = $options['prev_message'] ?? $this->buildPreviousMessage($this->translator->trans('Previous', [], 'pagerfanta'));
        $options['next_message'] = $options['next_message'] ?? $this->buildNextMessage($this->translator->trans('Next', [], 'pagerfanta'));

        return $this->view->render($pagerfanta, $routeGenerator, $options);
    }

    abstract protected function buildPreviousMessage(string $text): string;

    abstract protected function buildNextMessage(string $text): string;
}
