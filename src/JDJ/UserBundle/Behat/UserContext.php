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

class UserContext extends DefaultContext
{
    /**
     * @Given /^there are users:$/
     * @Given /^there are following users:$/
     * @Given /^the following users exist:$/
     * @Given /^il y a les utilisateurs suivants:$/
     */
    public function thereAreUsers(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var UserManager $userManager */
            $userManager = $this->getService("fos_user.user_manager");

            $user = $userManager->createUser();

            $user
                ->setUsername($data['username'])
                ->setEmail($data['email'])
                ->setPlainPassword($data['password'])
                ->setEnabled(("yes" === $data['enabled']) ? 1 : 0)
            ;

            $userManager->updateUser($user);
        }

    }
} 