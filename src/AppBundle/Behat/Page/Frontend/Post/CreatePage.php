<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Frontend\Post;

use AppBundle\Behat\Page\SymfonyPage;

class CreatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_post_create_by_topic';
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
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'comment' => '#app_post_body',
        ]);
    }
}
