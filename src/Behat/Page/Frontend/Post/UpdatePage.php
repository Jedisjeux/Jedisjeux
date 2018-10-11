<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\Post;

use App\Behat\Page\SymfonyPage;

class UpdatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_post_update';
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
        $this->getDocument()->pressButton('Save changes');
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
