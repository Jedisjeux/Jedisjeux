<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 15/02/15
 * Time: 22:18
 */

namespace JDJ\ComptaBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\Sens;
use JDJ\CoreBundle\Behat\DefaultContext;

class SensContext extends DefaultContext
{
    /**
     * @Given /^there are sens:$/
     * @Given /^there are following sens:$/
     * @Given /^the following sens exist:$/
     */
    public function thereAreSens(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $sens = new Sens();
            $sens
                ->setId(trim($data['id']))
                ->setLibelle(trim($data['libelle']))
            ;

            $manager->persist($sens);
        }

        $manager->flush();
    }
} 