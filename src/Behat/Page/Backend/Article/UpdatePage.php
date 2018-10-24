<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\Article;

use App\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;
use Behat\Mink\Exception\ElementNotFoundException;

class UpdatePage extends BaseUpdatePage
{
    /**
     * @param string $title
     *
     * @throws ElementNotFoundException
     */
    public function changeTitle($title)
    {
        $this->getElement('title')->setValue($title);
    }

    public function askForReview(): void
    {
        $this->getDocument()->pressButton('Ask for review');
    }

    public function askForPublication(): void
    {
        $this->getDocument()->pressButton('Ask for publication');
    }

    public function publish(): void
    {
        $this->getDocument()->pressButton('Publish');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => '#app_article_title',
        ]);
    }
}
