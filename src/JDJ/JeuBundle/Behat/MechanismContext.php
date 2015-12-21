<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 18/09/2014
 * Time: 23:57
 */

namespace JDJ\JeuBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Entity\Mechanism;

class MechanismContext extends DefaultContext
{
    /**
     * @Given /^game "([^""]*)" has following mechanisms:$/
     */
    public function gameHasFollowingMechanisms($jeuLibelle, TableNode $mecanismesTable)
    {
        $manager = $this->getEntityManager();

        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));

        foreach ($mecanismesTable->getRows() as $node) {

            $mecanismeLibelle = $node[0];

            $mecanisme = $this
                ->getRepository("mecanisme")
                ->findOneBy(array("libelle" => trim($mecanismeLibelle)))
            ;

            if (null === $mecanisme) {
                $mecanisme = new Mechanism();
                $mecanisme
                    ->setName(trim($mecanismeLibelle))
                ;
            }
            $jeu->addMechanism($mecanisme);

        }

        $manager->persist($jeu);
        $manager->flush();
    }

    /**
     * @Then /I am on mechanism "([^"]*)"$/
     */
    public function iAmOnMechanism($mecanismeLibelle)
    {
        /** @var Mechanism $mecanisme */
        $mecanisme = $this->findOneBy("mecanisme", array("libelle" => $mecanismeLibelle));
        $this->getSession()->visit($this->baseUrl."/jeu/mecanisme/".$mecanisme->getId()."/".$mecanisme->getSlug());
    }

    /**
     * @Then /I should be on mechanism "([^"]*)"$/
     */
    public function iShouldBeOnMechanism($mecanismeLibelle)
    {
        /** @var Mechanism $mecanisme */
        $mecanisme = $this->findOneBy("mecanisme", array("libelle" => $mecanismeLibelle));
        $this->assertSession()->addressEquals($this->baseUrl."/jeu/mecanisme/".$mecanisme->getId()."/".$mecanisme->getSlug());
        //file_put_contents("/var/www/cezembre/bidon/".str_replace(" ", "-", $page).".html", $this->getSession()->getPage()->getContent());
    }
} 