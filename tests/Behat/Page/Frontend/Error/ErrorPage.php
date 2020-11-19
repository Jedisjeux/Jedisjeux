<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\Error;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Behat\Mink\Element\NodeElement;

class ErrorPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return '_twig_error_test';
    }

    /**
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getTitle(): string
    {
        return $this->getElement('title')->getText();
    }

    /**
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getReturnToHomepageLink(): NodeElement
    {
        return $this->getElement('homepage_link');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => 'h2.title',
            'homepage_link' => '#logo a',
        ]);
    }
}
