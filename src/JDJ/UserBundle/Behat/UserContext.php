<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 20/09/2014
 * Time: 12:32
 */

namespace JDJ\UserBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use FOS\UserBundle\Doctrine\UserManager;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\UserBundle\Entity\User;

class UserContext extends DefaultContext
{
    /**
     * @Given /^there are users:$/
     * @Given /^there are following users:$/
     * @Given /^the following users exist:$/
     * @Given /^il y a les utilisateurs suivants:$/
     */
    public function thereAreUsers(TableNode $table){

        foreach ($table->getHash() as $data) {

            /** @var UserManager $userManager */
            $userManager = $this->getService("fos_user.user_manager");

            /** @var User $user */
            $user = $userManager->createUser();
            $user
                ->setUsername($data['username'])
                ->setEmail($data['email'])
                ->setPresentation($data['presentation'])
                ->setDateNaissance(new \DateTime($data['dateNaissance']))
                ->setPlainPassword($data['password'])
                ->setEnabled(("yes" === $data['enabled']) ? 1 : 0)

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