<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\ProductFile;

use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_product_file_index';
    }

    /**
     *
     * @throws ElementNotFoundException
     */
    public function countFiles(): int
    {
        return count($this->getElement('files')->findAll('css', '.comment'));
    }

    /**
     *
     * @throws ElementNotFoundException
     */
    public function hasNoFilesMessage(): bool
    {
        $filesContainerText = $this->getElement('files')->getText();

        return false !== strpos($filesContainerText, 'There are no files');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'files' => '#product-files',
        ]);
    }
}
