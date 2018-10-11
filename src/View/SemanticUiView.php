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

use App\View\Template\SemanticUiTemplate;
use Pagerfanta\View\DefaultView;

/**
 * SemanticUiView.
 *
 * View that can be used with the pagination module
 * from the Semantic UI CSS Toolkit
 * http://semantic-ui.com/
 *
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SemanticUiView extends DefaultView
{
    protected function createDefaultTemplate()
    {
        return new SemanticUiTemplate();
    }

    protected function getDefaultProximity()
    {
        return 3;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'semantic_ui';
    }
}
