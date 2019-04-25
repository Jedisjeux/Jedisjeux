<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\ProductFile;

use App\Behat\Behaviour\SpecifiesItsTitle;
use App\Behat\Behaviour\WorkflowActions;
use App\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;
use Behat\Mink\Exception\ElementNotFoundException;

class UpdatePage extends BaseUpdatePage
{
    use WorkflowActions,
        SpecifiesItsTitle;

    /**
     * @param string $path
     *
     * @throws ElementNotFoundException
     */
    public function attachFile(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $imageForm = $this->getElement('file');

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath.$path);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => '#app_product_file_title',
            'file' => '#app_product_file_file',
        ]);
    }
}
