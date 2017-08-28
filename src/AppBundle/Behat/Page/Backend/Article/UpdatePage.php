<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Backend\Article;

use AppBundle\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UpdatePage extends BaseUpdatePage
{
    /**
     * @param string $title
     */
    public function changeTitle($title)
    {
        $this->getElement('title')->setValue($title);
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
