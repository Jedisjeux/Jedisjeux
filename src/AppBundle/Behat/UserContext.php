<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat;

use AppBundle\Entity\User;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\User\Canonicalizer\Canonicalizer;
use Sylius\Component\User\Model\UserInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UserContext extends DefaultContext
{
    /**
     * @Given /^I am logged in as user "([^""]*)" with password "([^""]*)"$/
     */
    public function iAmLoggedInUserWithPassword($username, $password)
    {
        $this->visitPath("/login");
        $this->fillField("Nom d'utilisateur ou email", $username);
        $this->fillField('Mot de passe', $password);
        $this->pressButton('Connexion');
    }

    /**
     * @Given /^there are users:$/
     * @Given /^there are following users:$/
     * @Given /^the following users exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreUsers(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $user = $this->createUserByData($data);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @param array $data
     * @return UserInterface
     */
    protected function createUserByData(array $data)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->getCustomerFactory()->createNew();
        /** @var User $user */
        $user = $this->getUserFactory()->createNew();
        $user->setCustomer($customer);
        $this->populateUser($user, $data);
        $this->populateCustomer($customer, $data);

        $this->getContainer()->get('sylius.listener.password_updater')->updateUserPassword($user);

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param array $data
     */
    protected function populateUser(UserInterface $user, array $data)
    {
        $user->setUsername(isset($data['email']) ? trim($data['email']) : $this->faker->email);
        $user->setUsernameCanonical($this->getCanonicalizer()->canonicalize($user->getUsername()));
        $user->setPlainPassword(isset($data['password']) ? trim($data['password']) : $this->faker->password());
        $user->setEmail(isset($data['email']) ? trim($data['email']) : $this->faker->email);
        $user->setEmailCanonical($this->getCanonicalizer()->canonicalize($user->getEmail()));
        $user->setEnabled(isset($data['enabled']) ? (bool)$data['enabled'] : true);
        $user->setLocked(isset($data['locked']) ? (bool)$data['locked'] : false);
        $user->addRole(isset($data['role']) ? $data['role'] : 'ROLE_USER');

        $user->setPasswordResetToken(isset($data['confirmation_token']) ? $data['confirmation_token'] : null);

        if (isset($data['confirmation_token'])) {
            $user->setPasswordRequestedAt(isset($data['password_requested_At']) ? \DateTime::createFromFormat('Y-m-d', $data['password_requested_At']) : new \DateTime());
        }
    }

    /**
     * @param CustomerInterface $customer
     * @param array $data
     */
    protected function populateCustomer(CustomerInterface $customer, array $data)
    {
        $customer->setEmail(isset($data['email']) ? trim($data['email']) : $this->faker->email);
        $customer->setEmailCanonical($this->getCanonicalizer()->canonicalize($customer->getEmail()));
        $customer->setFirstName(isset($data['first_name']) ? trim($data['first_name']) : $this->faker->firstName);
        $customer->setLastName(isset($data['last_name']) ? trim($data['last_name']) : $this->faker->lastName);
    }

    /**
     * @return FactoryInterface
     */
    protected function getUserFactory()
    {
        return $this->getContainer()->get('sylius.factory.shop_user');
    }

    /**
     * @return FactoryInterface
     */
    protected function getCustomerFactory()
    {
        return $this->getContainer()->get('sylius.factory.customer');
    }

    /**
     * @return Canonicalizer
     */
    protected function getCanonicalizer()
    {
        return $this->getContainer()->get('sylius.canonicalizer');
    }
}