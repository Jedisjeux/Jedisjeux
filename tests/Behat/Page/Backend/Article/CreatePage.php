<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Article;

use Behat\Mink\Exception\ElementNotFoundException;
use Monofony\Bridge\Behat\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_article_create';
    }

    /**
     * @param string $title
     *
     * @throws ElementNotFoundException
     */
    public function specifyTitle($title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     *
     * @throws ElementNotFoundException
     */
    public function attachImage(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $imageForm = $this->getElement('image');

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath.$path);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'image' => '#app_article_mainImage',
            'title' => '#app_article_title',
        ]);
    }
}
