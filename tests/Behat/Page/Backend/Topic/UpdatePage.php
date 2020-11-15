<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Topic;

use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_topic_update';
    }

    /**
     * @param string $title
     */
    public function changeTitle($title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string $body
     */
    public function changeBody($body)
    {
        $this->getElement('body')->setValue($body);
    }

    /**
     * @param string $category
     */
    public function changeCategory($category)
    {
        $this->getElement('category')->setValue($category);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => '#app_topic_title',
            'category' => '#app_topic_mainTaxon',
            'body' => '#app_topic_mainPost_body',
        ]);
    }
}
