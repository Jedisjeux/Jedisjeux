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
 * FrontendTranslatedView.
 *
 * This view renders the frontend view with the text translated.
 */
class FrontendTranslatedView extends TranslatedView
{
    protected function buildPreviousMessage(string $text): string
    {
        return sprintf('<i class="fa fa-angle-left"><span class="sr-only">%s</span></i>', $text);
    }

    protected function buildNextMessage(string $text): string
    {
        return sprintf('<i class="fa fa-angle-right"><span class="sr-only">%s</span></i>', $text);
    }

    public function getName(): string
    {
        return 'frontend_translated';
    }
}
