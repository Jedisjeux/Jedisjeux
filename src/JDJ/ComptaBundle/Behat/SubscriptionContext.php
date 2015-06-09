<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 09/06/2015
 * Time: 14:07
 */

namespace JDJ\ComptaBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\Product;
use JDJ\ComptaBundle\Entity\Subscription;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class SubscriptionContext extends DefaultContext
{
    /**
     * @Given /^there are subscriptions:$/
     * @Given /^there are following subscriptions:$/
     * @Given /^the following subscriptions exist:$/
     */
    public function thereAreProducts(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var Product $product */
            $product = $this->findOneBy('comptaProduct', array('name' => $data['product']));

            $subscription = new Subscription();
            $subscription
                ->setProduct($product)
                ->setStartAt(\DateTime::createFromFormat('Y-m-d', $data['start_at']))
                ->setEndAt(\DateTime::createFromFormat('Y-m-d', $data['end_at']))
            ;

            $manager->persist($subscription);
        }

        $manager->flush();
    }
}