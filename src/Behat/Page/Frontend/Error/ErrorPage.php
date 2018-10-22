<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\Error;

use App\Behat\Page\SymfonyPage;
use Behat\Mink\Element\NodeElement;

class ErrorPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return '_twig_error_test';
    }

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
     * @return NodeElement
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
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => 'h2.title',
            'homepage_link' => '#logo a',
        ]);
    }
}
