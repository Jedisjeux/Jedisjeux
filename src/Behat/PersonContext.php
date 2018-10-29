<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat;

use App\Entity\Person;
use Behat\Gherkin\Node\TableNode;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonContext extends DefaultContext
{
    /**
     * @Given /^there are people:$/
     * @Given /^there are following people:$/
     * @Given /^the following people exist:$/
     *
     * @param TableNode $table
     */
    public function thereArePeople(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var Person $person */
            $person = $this->getFactory('person', 'app')->createNew();
            $person->setFirstName(isset($data['first_name']) ? $data['first_name'] : $this->faker->firstName);
            $person->setLastName(isset($data['last_name']) ? $data['last_name'] : $this->faker->lastName);

            $manager->persist($person);
        }

        $manager->flush();
    }
}
