<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\View\Template;

use Pagerfanta\View\Template\SemanticUiTemplate as BaseSemanticUiTemplate;

class SemanticUiTemplate extends BaseSemanticUiTemplate
{
    protected function getDefaultOptions(): array
    {
        return array_merge(parent::getDefaultOptions(), [
            'prev_message' => '&larr; Previous',
            'next_message' => 'Next &rarr;',
            'css_container_class' => 'ui stackable fluid pagination menu',
            'css_prev_class' => 'prev',
            'css_next_class' => 'next',
        ]);
    }
}
