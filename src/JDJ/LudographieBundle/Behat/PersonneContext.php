<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 19/09/2014
 * Time: 00:00
 */

namespace JDJ\LudographieBundle\Behat;

use Behat\Gherkin\Node\TableNode;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\LudographieBundle\Entity\Personne;
use JDJ\WebBundle\Entity\Pays;

class PersonneContext extends DefaultContext
{

    /**
     * @Given /^there are personnes:$/
     * @Given /^there are following personnes:$/
     * @Given /^the following personnes exist:$/
     * @Given /^il y a les personnes suivantes:$/
     */
    public function thereArePersonnes(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $pays = $this->getRepository("country")->findOneBy(array("libelle" => $data['country']));

            if (null === $pays) {
                $pays = new Pays();
                $pays->setLibelle($data['country']);

                $manager->persist($pays);
            }

            $personne = new Personne();
            $personne
                ->setPrenom(trim($data['prenom']))
                ->setNom(trim($data['nom']))
                ->setCountry($pays)
                ->setSiteWeb(isset($data['site_web']) ? $data['site_web'] : null)
            ;

            $manager->persist($personne);
        }

        $manager->flush();
    }

    /**
     * @Given /^game "([^""]*)" has following authors:$/
     */
    public function gameHasFollowingAuthors($jeuLibelle, TableNode $personnesTable)
    {
        $manager = $this->getEntityManager();

        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));

        $personnes = $this->getPersonnes($personnesTable->getRows());
        /** @var Personne $personne */
        foreach($personnes as $personne) {
            /* TODO: see why we have to add in these two entities */
            $jeu->addAuteur($personne);
            $personne->addAuteurJeux($jeu);
        }

        $manager->persist($jeu);
        $manager->flush();
    }

    /**
     * @Given /^game "([^""]*)" has following illustrators:$/
     */
    public function gameHasFollowingIllustrators($jeuLibelle, TableNode $personnesTable)
    {
        $manager = $this->getEntityManager();

        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));

        $personnes = $this->getPersonnes($personnesTable->getRows());
        foreach($personnes as $personne) {
            $jeu->addIllustrateur($personne);
        }

        $manager->persist($jeu);
        $manager->flush();
    }

    /**
     * @Given /^game "([^""]*)" has following editors:$/
     */
    public function gameHasFollowingEditors($jeuLibelle, TableNode $personnesTable)
    {
        $manager = $this->getEntityManager();

        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));

        $personnes = $this->getPersonnes($personnesTable->getRows());
        foreach($personnes as $personne) {
            $jeu->addEditeur($personne);
        }

        $manager->persist($jeu);
        $manager->flush();
    }

    /**
     * @Then /I am on ludography of "([^"]*)"$/
     */
    public function iAmOnLudography($personneLibelle)
    {
        list($prenom, $nom) = explode(" ", $personneLibelle);

        /** @var Personne $personne */
        $personne = $this->findOneBy("personne", array("prenom" => $prenom, "nom" => $nom));
        $this->getSession()->visit("/ludographie/".$personne->getId()."/".$personne->getSlug());
        file_put_contents(__DIR__.'/../../../../web/behat/'.$personne->getSlug().'.html', $this->getSession()->getPage()->getContent());
    }

    /**
     * @Then /I should be on ludography of "([^"]*)"$/
     */
    public function iShouldBeOnLudography($personneLibelle)
    {
        list($prenom, $nom) = explode(" ", $personneLibelle);

        /** @var Personne $personne */
        $personne = $this->findOneBy("personne", array("prenom" => $prenom, "nom" => $nom));
        $this->assertSession()->addressEquals($this->baseUrl."/ludographie/".$personne->getId()."/".$personne->getSlug());
    }

    /**
     * @param array $nodes
     * @return array
     */
    private function getPersonnes(array $nodes)
    {
        $personnes = array();

        foreach($nodes as $node)
        {
            list($prenom, $nom) = $node;

            /** @var Personne $personne */
            $personne = $this
                ->getRepository("personne")
                ->findOneBy(array("nom" => trim($nom), "prenom" => trim($prenom)))
            ;

            if (null === $personne) {
                $personne = new Personne();
                $personne
                    ->setNom(trim($nom))
                    ->setPrenom(trim($prenom))
                ;
            }



            $personnes[] = $personne;
        }

        return $personnes;

    }
} 