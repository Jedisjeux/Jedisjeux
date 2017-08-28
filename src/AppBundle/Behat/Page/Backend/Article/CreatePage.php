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

use AppBundle\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $title
     */
    public function specifyTitle($title)
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
