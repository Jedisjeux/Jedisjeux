<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 09/06/2015
 * Time: 13:39
 */

namespace JDJ\ComptaBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class ProductContext extends DefaultContext
{
    /**
     * @Given /^there are products:$/
     * @Given /^there are following products:$/
     * @Given /^the following products exist:$/
     */
    public function thereAreProducts(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $product = new Product();
            $product
                ->setName(isset($data['name']) ? trim($data['name']) : $this->faker->name)
                ->setPrice(isset($data['price']) ? $data['price'] : $this->faker->randomFloat(2))
                ->setSubscriptionDuration(isset($data['subscription_duration']) ? $data['subscription_duration'] : $this->faker->numberBetween(1, 24))
            ;

            $manager->persist($product);
        }

        $manager->flush();
    }
}