<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Page\Backend\Customer;

use App\Tests\Behat\Bridge\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    public function getRouteName(): string
    {
        return 'sylius_backend_customer_create';
    }

    public function specifyEmail(string $email): void
    {
        $this->getElement('email')->setValue($email);
    }

    public function createAccountWithPassword(string $password): void
    {
        $this->getElement('create_user')->check();
        $this->getElement('password')->setValue($password);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'create_user' => '#app_customer_createUser',
            'email' => '#app_customer_email',
            'password' => '#app_customer_user_plainPassword',
        ]);
    }
}
