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

/**
 * SemanticUiTranslatedView.
 *
 * This view renders the semantic ui view with the text translated.
 */
class SemanticUiTranslatedView extends TranslatedView
{
    protected function buildPreviousMessage(string $text): string
    {
        return sprintf('&larr; %s', $text);
    }

    protected function buildNextMessage(string $text): string
    {
        return sprintf('%s &rarr;', $text);
    }

    public function getName(): string
    {
        return 'semantic_ui_translated';
    }
}
