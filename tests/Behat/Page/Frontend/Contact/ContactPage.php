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

namespace App\Tests\Behat\Page\Frontend\Contact;

use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class ContactPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_contact_request_create';
    }

    /**
     * @param string $firstName
     */
    public function specifyFirstName($firstName)
    {
        $this->getElement('first_name')->setValue($firstName);
    }

    /**
     * @param string $lastName
     */
    public function specifyLastName($lastName)
    {
        $this->getElement('last_name')->setValue($lastName);
    }

    /**
     * @param string $email
     */
    public function specifyEmail($email)
    {
        $this->getElement('email')->setValue($email);
    }

    /**
     * @param string $message
     */
    public function specifyMessage($message)
    {
        $this->getElement('message')->setValue($message);
    }

    public function send()
    {
        $this->getDocument()->pressButton('Send');
    }

    /**
     * {@inheritdoc}
     *
     * @throws ElementNotFoundException
     */
    public function getValidationMessageFor($element)
    {
        $errorLabel = $this->getElement($element)->getParent()->find('css', '.sylius-validation-error');

        if (null === $errorLabel) {
            throw new ElementNotFoundException(
                $this->getSession(),
                'Validation message', 'css', '.sylius-validation-error')
            ;
        }

        return $errorLabel->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'first_name' => '#app_contact_request_firstName',
            'last_name' => '#app_contact_request_lastName',
            'email' => '#app_contact_request_email',
            'message' => '#app_contact_request_body',
        ]);
    }
}
