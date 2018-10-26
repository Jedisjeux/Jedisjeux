<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\Topic;

use App\Behat\Page\SymfonyPage;
use Behat\Mink\Exception\ElementNotFoundException;

class CreatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_topic_create';
    }

    /**
     * @param string|null $title
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setTitle(?string $title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string|null $comment
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setComment(?string $comment)
    {
        $this->getElement('comment')->setValue($comment);
    }

    public function submit()
    {
        $this->getDocument()->pressButton('Create');
    }

    /**
     * {@inheritdoc}
     *
     * @throws ElementNotFoundException
     */
    public function checkValidationMessageFor($element, $message)
    {
        $errorLabel = $this->getElement($element)->getParent()->getParent()->find('css', '.form-error-message');

        if (null === $errorLabel) {
            throw new ElementNotFoundException($this->getSession(), 'Validation message', 'css', '.form-error-message');
        }

        return $message === $errorLabel->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'comment' => '#app_topic_mainPost_body',
            'title' => '#app_topic_title',
        ]);
    }
}
