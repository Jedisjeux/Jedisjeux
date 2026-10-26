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
use Pagerfanta\View\SemanticUiView as BaseSemanticUiView;
use Pagerfanta\View\Template\TemplateInterface;

class SemanticUiView extends BaseSemanticUiView
{
    protected function createDefaultTemplate(): TemplateInterface
    {
        return new SemanticUiTemplate();
    }

    protected function getDefaultProximity(): int
    {
        return 3;
    }

    public function getName(): string
    {
        return 'semantic_ui';
    }
}
