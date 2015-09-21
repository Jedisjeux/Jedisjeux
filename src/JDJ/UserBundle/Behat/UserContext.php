<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 20/09/2014
 * Time: 12:32
 */

namespace JDJ\UserBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use Faker\Factory;
use FOS\UserBundle\Doctrine\UserManager;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\UserBundle\Entity\User;

class UserContext extends DefaultContext
{
    /**
     * @Given /^I am logged in as user "([^""]*)" with password "([^""]*)"$/
     */
    public function iAmLoggedInUserWithPassword($username, $password)
    {
        $this->visitPath("/login");
        $this->fillField("Nom d'utilisateur", $username);
        $this->fillField('Mot de passe', $password);
        $this->pressButton('Je me connecte');
    }

    /**
     * @Given /^there are users:$/
     * @Given /^there are following users:$/
     * @Given /^the following users exist:$/
     * @Given /^il y a les utilisateurs suivants:$/
     */
    public function thereAreUsers(TableNode $table)
    {
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create();

        foreach ($table->getHash() as $data) {

            /** @var UserManager $userManager */
            $userManager = $this->getService("fos_user.user_manager");

            /** @var User $user */
            $user = $userManager->createUser();
            $user
                ->setUsername(isset($data['username']) ? $data['username'] : $faker->userName)
                ->setEmail(isset($data['email']) ? $data['email'] : $faker->email)
                ->setPresentation(isset($data['presentation']) ? $data['presentation'] : $faker->text())
                ->setDateNaissance(isset($data['dateNaissance']) ? \DateTime::createFromFormat('Y-m-d', $data['dateNaissance']) : \DateTime::createFromFormat('Y-m-d', $faker->date()))
                ->setPlainPassword(isset($data['password']) ? $data['password'] : $faker->password)
                ->setEnabled(isset($data['enabled']) ? $data['enabled'] : true)
            ;

            $userManager->updateUser($user);
        }
    }

    /**
     * @Given /^user "([^""]*)" has following roles:$/
     */
    public function userHasFollowingRoles($userName, TableNode $rolesTable)
    {
        $manager = $this->getEntityManager();

        /** @var User $user */
        $user = $this->findOneBy("user", array("username" => $userName));

        foreach ($rolesTable->getRows() as $node) {

            $role = $node[0];
            $user->addRole($role);

        }

        $manager->persist($user);
        $manager->flush();
    }


} 