<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ProductFile;

use App\Tests\Behat\Behaviour\SpecifiesItsTitle;
use App\Tests\Behat\Behaviour\WorkflowActions;
use Behat\Mink\Exception\ElementNotFoundException;
use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;
use Webmozart\Assert\Assert;

class UpdatePage extends AbstractUpdatePage
{
    use WorkflowActions;
    use SpecifiesItsTitle;

    public function getRouteName(): string
    {
        return 'app_backend_product_file_update';
    }

    /**
     * @throws ElementNotFoundException
     */
    public function attachFile(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $imageForm = $this->getElement('file');

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath.$path);
    }

    public function getFileError(): string
    {
        $file = $this->getElement('file')->find('css', '.sylius-validation-error');
        Assert::notNull($file);

        return $file->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => '#app_product_file_title',
            'file' => '#app_product_file',
        ]);
    }
}
