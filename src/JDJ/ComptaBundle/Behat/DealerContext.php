<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/12/2015
 * Time: 13:54
 */

namespace JDJ\ComptaBundle\Behat;
use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\Address;
use JDJ\ComptaBundle\Entity\Dealer;
use JDJ\CoreBundle\Behat\DefaultContext;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerContext extends DefaultContext
{
    /**
     * @Given /^there are dealers:$/
     * @Given /^there are following dealers:$/
     * @Given /^the following dealers exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreDealers(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $address = new Address();
            $address
                ->setStreet(isset($data['street']) ? $data['street'] : $this->faker->streetAddress)
                ->setAdditionalAddressInfo(isset($data['address_more']) ? $data['address_more'] : null)
                ->setPostalCode(isset($data['post_code']) ? $data['post_code'] : $this->faker->postcode)
                ->setCity(isset($data['city']) ? $data['city'] : $this->faker->city);

            $dealer = new Dealer();
            $dealer
                ->setName(isset($data['name']) ? $data['name'] : $this->faker->name)
                ->setAddress($address)
            ;

            $manager->persist($dealer);
        }

        $manager->flush();
    }
}