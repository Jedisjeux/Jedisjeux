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
use AppBundle\Formatter\StringInflector;

class RegisterPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName()
    {
        return 'app_frontend_register';
    }

    /**
     * {@inheritdoc}
     *
     * @throws ElementNotFoundException
     */
    public function checkValidationMessageFor($element, $message)
    {
        $errorLabel = $this
            ->getElement(StringInflector::nameToCode($element))
            ->getParent()
            ->find('css', '.sylius-validation-error')
        ;

        if (null === $errorLabel) {
            throw new ElementNotFoundException($this->getSession(), 'Validation message', 'css', '.sylius-validation-error');
        }

        return $message === $errorLabel->getText();
    }

    public function register()
    {
        $this->getDocument()->pressButton('Create an account');
    }

    /**
     * {@inheritdoc}
     */
    public function specifyEmail($email)
    {
        $this->getDocument()->fillField('Email', $email);
    }

    /**
     * {@inheritdoc}
     */
    public function specifyPassword($password)
    {
        $this->getDocument()->fillField('Password', $password);
    }

    /**
     * {@inheritdoc}
     */
    public function specifyPhoneNumber($phoneNumber)
    {
        $this->getDocument()->fillField('Phone number', $phoneNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function verifyPassword($password)
    {
        $this->getDocument()->fillField('Verification', $password);
    }

    public function subscribeToTheNewsletter()
    {
        $this->getDocument()->checkField('Subscribe to the newsletter');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'email' => '#sylius_customer_registration_email',
            'first_name' => '#sylius_customer_registration_firstName',
            'last_name' => '#sylius_customer_registration_lastName',
            'password_verification' => '#sylius_customer_registration_user_plainPassword_second',
            'password' => '#sylius_customer_registration_user_plainPassword_first',
            'phone_number' => '#sylius_customer_registration_phoneNumber',
        ]);
    }
}
