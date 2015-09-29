<?php
/**
 * Created by PhpStorm.
 * User: loic_fremont
 * Date: 09/06/2015
 * Time: 11:32
 */

namespace JDJ\ComptaBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\BookEntry;
use JDJ\ComptaBundle\Entity\PaymentMethod;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BookEntryContext extends DefaultContext
{
    /**
     * @Given /^there are book entries:$/
     * @Given /^there are following book entries:$/
     * @Given /^the following book entries exist:$/
     */
    public function thereAreBookEntries(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = $this->findOneBy('comptaPaymentMethod', array('name' => $data['payment_method']));

            $bookEntry = new BookEntry();
            $bookEntry
                ->setLabel(trim($data['label']))
                ->setPrice(isset($data['price']) ? $data['price'] : $this->faker->randomFloat(2))
                ->setActiveAt(isset($data['active_at']) ? \DateTime::createFromFormat('Y-m-d', $data['active_at']) : \DateTime::createFromFormat('Y-m-d', $this->faker->date()))
                ->setState(isset($data['state']) ? $data['state'] : BookEntry::STATE_DEBITED)
                ->setPaymentMethod($paymentMethod);
            ;

            $manager->persist($bookEntry);
        }

        $manager->flush();
    }
}