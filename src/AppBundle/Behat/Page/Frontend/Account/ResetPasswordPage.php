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

class ResetPasswordPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'sylius_frontend_password_reset';
    }

    public function reset()
    {
        $this->getDocument()->pressButton('Reset');
    }

    /**
     * @param string $password
     */
    public function specifyNewPassword(string $password)
    {
        $this->getElement('password')->setValue($password);
    }

    /**
     * @param string $password
     */
    public function specifyConfirmPassword($password)
    {
        $this->getElement('confirm_password')->setValue($password);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'password' => '#sylius_user_reset_password_password_first',
            'confirm_password' => '#sylius_user_reset_password_password_second',
        ]);
    }
}
