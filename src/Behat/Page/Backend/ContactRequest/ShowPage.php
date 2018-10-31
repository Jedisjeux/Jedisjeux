<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\ContactRequest;

use App\Behat\Page\SymfonyPage;
use Behat\Behat\Context\Context;
use Behat\Mink\Exception\ElementNotFoundException;

class ShowPage extends SymfonyPage implements Context
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_backend_contact_request_show';
    }

    /**
     * @return string
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
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'email' => '#email',
        ]);
    }
}
