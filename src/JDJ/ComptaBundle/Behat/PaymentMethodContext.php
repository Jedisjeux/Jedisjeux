<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 15/02/15
 * Time: 22:01
 */

namespace JDJ\ComptaBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\PaymentMethod;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class PaymentMethodContext extends DefaultContext
{
    /**
     * @Given /^there are payment methods:$/
     * @Given /^there are following payment methods:$/
     * @Given /^the following payment methods exist:$/
     */
    public function thereArePaymentMethods(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $paymentMethod = new PaymentMethod();
            $paymentMethod
                ->setName(trim($data['name']))
            ;

            $manager->persist($paymentMethod);
        }

        $manager->flush();
    }
} 