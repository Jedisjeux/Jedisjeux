<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Post;

use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_post_update';
    }

    /**
     * @param string $body
     */
    public function changeBody($body)
    {
        $this->getElement('body')->setValue($body);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'body' => '#app_post_body',
        ]);
    }
}
