<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\View;

use WhiteOctober\PagerfantaBundle\View\TwitterBootstrap4TranslatedView;

/**
 * SemanticUiTranslatedView
 *
 * This view renders the semantic ui view with the text translated.
 */
class FrontendTranslatedView extends TwitterBootstrap4TranslatedView
{
    protected function buildPreviousMessage($text)
    {
        return '<i class="fa fa-angle-left"><span class="sr-only">Previous</span></i>';
    }

    protected function buildNextMessage($text)
    {
        return '<i class="fa fa-angle-right"><span class="sr-only">Next</span></i>';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'frontend_translated';
    }
}
