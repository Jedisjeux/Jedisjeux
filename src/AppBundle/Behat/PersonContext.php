<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 30/03/16
 * Time: 08:17
 */

namespace AppBundle\Behat;
use AppBundle\Entity\Person;
use Behat\Gherkin\Node\TableNode;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PersonContext extends DefaultContext
{
    /**
     * @Given /^there are persons:$/
     * @Given /^there are following persons:$/
     * @Given /^the following persons exist:$/
     *
     * @param TableNode $table
     */
    public function thereArePersons(TableNode $table)
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