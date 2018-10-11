<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\Article;

use App\Behat\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_article_index';
    }

    /**
     * @return int
     */
    public function countArticlesItems()
    {
        $productsList = $this->getDocument()->find('css', '#article-list');

        $products = $productsList->findAll('css', '.image-box');

        return count($products);
    }

    /**
     * @return string
     */
    public function getFirstArticleTitleFromList()
    {
        $productsList = $this->getDocument()->find('css', '#article-list');

        return $productsList->find('css', '.image-box:first-child .lead')->getText();
    }

    /**
     * @param string $title
     *
     * @return bool
     */
    public function isArticleOnList($title)
    {
        return null !== $this->getDocument()->find('css', sprintf('#article-list .lead:contains("%s")', $title));
    }

    /**
     * @return bool
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function hasNoArticlesMessage(): bool
    {
        $articlesContainerText = $this->getElement('articles')->getText();

        return false !== strpos($articlesContainerText, 'There are no articles');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'articles' => '#article-list',
        ]);
    }
}
