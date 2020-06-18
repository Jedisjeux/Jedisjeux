<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Article;

use App\Tests\Behat\Behaviour\WorkflowActions;
use App\Tests\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;
use Behat\Mink\Exception\ElementNotFoundException;

class UpdatePage extends BaseUpdatePage
{
    use WorkflowActions;

    /**
     * @param string $title
     *
     * @throws ElementNotFoundException
     */
    public function changeTitle($title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string $path
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
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasMainImage(): bool
    {
        $image = $this->getElement('image');
        $item = $image->find('css', 'img');

        return null !== $item;
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
