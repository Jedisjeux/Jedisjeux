<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ContactRequest;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;

class ShowPage extends SymfonyPage implements Context
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_backend_contact_request_show';
    }

    /**
     *
     * @throws ElementNotFoundException
     */
    public function getEmail(): string
    {
        return $this->getElement('email')->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'email' => '#email',
        ]);
    }
}
