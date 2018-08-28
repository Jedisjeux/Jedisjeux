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

namespace AppBundle\Behat\Page\Frontend\Account;

use Behat\Mink\Exception\ElementNotFoundException;
use AppBundle\Behat\Page\SymfonyPage;

class ChangePasswordPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'sylius_frontend_account_change_password';
    }

    /**
     * {@inheritdoc}
     */
    public function checkValidationMessageFor($element, $message)
    {
        $errorLabel = $this->getElement($element)->getParent()->find('css', '.help-');

        if (null === $errorLabel) {
            throw new ElementNotFoundException($this->getSession(), 'Validation message', 'css', '.help-');
        }

        return $message === $errorLabel->getText();
    }

    /**
     * @param string $password
     */
    public function specifyCurrentPassword($password)
    {
        $this->getElement('current_password')->setValue($password);
    }

    /**
     * @param string $password
     */
    public function specifyNewPassword($password)
    {
        $this->getElement('new_password')->setValue($password);
    }

    /**
     * @param string $password
     */
    public function specifyConfirmationPassword($password)
    {
        $this->getElement('confirmation')->setValue($password);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'confirmation' => '#sylius_user_change_password_newPassword_second',
            'current_password' => '#sylius_user_change_password_currentPassword',
            'new_password' => '#sylius_user_change_password_newPassword_first',
        ]);
    }
}
