<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/09/15
 * Time: 22:58
 */

namespace JDJ\ComptaBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use Faker\Factory;
use JDJ\ComptaBundle\Entity\Address;
use JDJ\ComptaBundle\Entity\Customer;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class CustomerContext extends DefaultContext
{
    /**
     * @Given /^there are customers:$/
     * @Given /^there are following customers:$/
     * @Given /^the following customers exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreCustomers(TableNode $table)
    {
        $manager = $this->getEntityManager();

        // use the factory to create a Faker\Generator instance
        $faker = Factory::create();

        foreach ($table->getHash() as $data) {

            $address = new Address();
            $address
                ->setStreet(isset($data['street']) ? $data['street'] : $faker->streetAddress)
                ->setAdditionalAddressInfo(isset($data['address_more']) ? $data['address_more'] : null)
                ->setPostalCode(isset($data['post_code']) ? $data['post_code'] : $faker->postcode)
                ->setCity(isset($data['city']) ? $data['city'] : $faker->city);

            $customer = new Customer();
            $customer
                ->setEmail(isset($data['email']) ? $data['email'] : $faker->email)
                ->setSociety(isset($data['company']) ? $data['company'] : $faker->company)
                ->setAddress($address)
            ;

            $manager->persist($customer);
        }

        $manager->flush();
    }

}