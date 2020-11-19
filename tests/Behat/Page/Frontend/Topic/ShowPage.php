<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\Topic;

use App\Tests\Behat\Page\Frontend\Post as PostPage;

class ShowPage extends PostPage\IndexPage
{
    /**
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
