<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Page\Frontend\Account;

use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class ResetPasswordPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'sylius_frontend_password_reset';
    }

    public function reset()
    {
        $this->getDocument()->pressButton('Reset');
    }

    /**
     */
    public function specifyNewPassword(?string $password)
    {
        $this->getElement('password')->setValue($password);
    }

    /**
     */
    public function specifyConfirmPassword(?string $password)
    {
        $this->getElement('confirm_password')->setValue($password);
    }

    /**
     *
     *
     * @throws ElementNotFoundException
     */
    public function checkValidationMessageFor(string $element, string $message): bool
    {
        $errorLabel = $this->getElement($element)->getParent()->getParent()->find('css', '.form-error-message');

        if (null === $errorLabel) {
            throw new ElementNotFoundException($this->getSession(), 'Validation message', 'css', '.form-error-message');
        }

        return $message === $errorLabel->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'password' => '#sylius_user_reset_password_password_first',
            'confirm_password' => '#sylius_user_reset_password_password_second',
        ]);
    }
}
