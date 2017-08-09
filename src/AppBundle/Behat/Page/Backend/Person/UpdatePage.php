<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Backend\Person;

use AppBundle\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
class UpdatePage extends BaseUpdatePage
{
    /**
     * @param string $firstName
     */
    public function changeFirstName($firstName)
    {
        $this->getElement('first_name')->setValue($firstName);
    }

    /**
     * @param string $lastName
     */
    public function changeLastName($lastName)
    {
        $this->getElement('last_name')->setValue($lastName);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'first_name' => '#person_fisrtName',
            'last_name' => '#person_lastName',
        ]);
    }
}
