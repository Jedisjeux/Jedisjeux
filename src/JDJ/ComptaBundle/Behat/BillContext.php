<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 09/06/2015
 * Time: 15:57
 */

namespace JDJ\ComptaBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\Bill;
use JDJ\ComptaBundle\Entity\Customer;
use JDJ\ComptaBundle\Entity\PaymentMethod;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BillContext extends DefaultContext
{
    /**
     * @Given /^there are bills:$/
     * @Given /^there are following bills:$/
     * @Given /^the following bills exist:$/
     */
    public function thereAreBills(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var Customer $customer */
            $customer = $this->findOneBy('comptaCustomer', array('societyName' => $data['society']));

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $this->findOneBy('comptaPaymentMethod', array('name' => $data['payment_method']));

            $bill = new Bill();
            $bill
                ->setCustomer($customer)
                ->setPaymentMethod($paymentMethod)
                ->setPaidAt(isset($data['paid_at']) ? \DateTime::createFromFormat('Y-m-d', $data['paid_at']) : null)
            ;

            $manager->persist($bill);
        }

        $manager->flush();
    }
}