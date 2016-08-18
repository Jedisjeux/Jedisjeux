<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/08/2015
 * Time: 10:51
 */

namespace AppBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\User\Model\CustomerInterface;
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

            $customer = $this->createCustomerByData($data);
            $manager->persist($customer);
        }

        $manager->flush();
    }

    /**
     * @param array $data
     * @return CustomerInterface
     */
    protected function createCustomerByData(array $data)
    {
        /** @var CustomerInterface $customer */
        $customer = $this->getCustomerFactory()->createNew();
        /** @var UserInterface $user */
        $user = $this->getUserFactory()->createNew();
        $customer->setUser($user);
        $this->populateUser($user, $data);
        $this->populateCustomer($customer, $data);

        $this->getContainer()->get('sylius.user.password_updater')->updatePassword($user);
        return $customer;
    }

    /**
     * @param UserInterface $user
     * @param array $data
     */
    protected function populateUser(UserInterface $user, array $data)
    {
        $user->setUsername(isset($data['email']) ? trim($data['email']) : $this->faker->email);
        $user->setPlainPassword(isset($data['password']) ? trim($data['password']) : $this->faker->password());
        $user->setEmail(isset($data['email']) ? trim($data['email']) : $this->faker->email);
        $user->setEnabled(isset($data['enabled']) ? (bool)$data['enabled'] : true);
        $user->setLocked(isset($data['locked']) ? (bool)$data['locked'] : false);
        $user->addRole(isset($data['role']) ? $data['role'] : 'ROLE_USER');

        $user->setConfirmationToken(isset($data['confirmation_token']) ? $data['confirmation_token'] : null);

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
        $customer->setFirstName(isset($data['first_name']) ? trim($data['first_name']) : $this->faker->firstName);
        $customer->setLastName(isset($data['last_name']) ? trim($data['last_name']) : $this->faker->lastName);
    }

    /**
     * @return FactoryInterface
     */
    protected function getUserFactory()
    {
        return $this->getContainer()->get('sylius.factory.user');
    }

    /**
     * @return FactoryInterface
     */
    protected function getCustomerFactory()
    {
        return $this->getContainer()->get('sylius.factory.customer');
    }
}