<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Page\Backend\Customer;

use App\Tests\Behat\Behaviour\Toggles;
use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    use Toggles;

    public function getRouteName(): string
    {
        return 'sylius_backend_customer_update';
    }

    /**
     * @param null|string $email
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function changeEmail(?string $email)
    {
        $this->getElement('email')->setValue($email);
    }

    /**
     * @return null|string
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function getEmail(): ?string
    {
        return $this->getElement('email')->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function changePassword($password)
    {
        $this->getDocument()->fillField('Password', $password);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->getElement('password');
    }

    /**
     * {@inheritdoc}
     */
    protected function getToggleableElement()
    {
        return $this->getElement('enabled');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'email' => '#app_customer_email',
            'enabled' => '#app_customer_user_enabled',
            'password' => '#app_customer_user_password',
        ]);
    }
}
