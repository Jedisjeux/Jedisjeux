<?php

namespace App\Behat\Page\Frontend\Topic;

use App\Behat\Page\Frontend\Post as PostPage;

class ShowPage extends PostPage\IndexPage
{
    /**
     * @return string
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getTitle(): string
    {
        return $this->getElement('title')->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => 'h1.page-title',
        ]);
    }
}
