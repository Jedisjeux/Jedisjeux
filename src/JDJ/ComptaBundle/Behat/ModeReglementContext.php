<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 15/02/15
 * Time: 22:01
 */

namespace JDJ\ComptaBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use JDJ\ComptaBundle\Entity\ModeReglement;
use JDJ\CoreBundle\Behat\DefaultContext;

/**
 * Class ModeReglementContext
 * @package JDJ\ComptaBundle\Behat
 */
class ModeReglementContext extends DefaultContext
{
    /**
     * @Given /^there are modes reglement:$/
     * @Given /^there are following modes reglement:$/
     * @Given /^the following modes reglement exist:$/
     */
    public function thereAreModesReglement(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $modeReglement = new ModeReglement();
            $modeReglement
                ->setLibelle(trim($data['libelle']))
            ;

            $manager->persist($modeReglement);
        }

        $manager->flush();
    }
} 