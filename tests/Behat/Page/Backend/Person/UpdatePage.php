<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Person;

use Monofony\Bundle\AdminBundle\Tests\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_person_update';
    }

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
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'first_name' => '#app_person_firstName',
            'last_name' => '#app_person_lastName',
        ]);
    }
}
