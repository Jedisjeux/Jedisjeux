<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 18/09/2014
 * Time: 21:34
 */

namespace JDJ\JeuBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\WebBundle\Entity\Statut;

class JeuContext extends DefaultContext
{
    /**
     * @Given /^there are games:$/
     * @Given /^there are following games:$/
     * @Given /^the following games exist:$/
     * @Given /^il y a les jeux suivants:$/
     */
    public function thereAreGames(TableNode $table){
        $manager = $this->getEntityManager();

        $published = $this->findOneBy("statut", array("code" => Statut::PUBLISHED));

        foreach ($table->getHash() as $data) {

            /** @var Statut $statut */
            if (isset($data['statut'])) {
                $statut = $this->findOneBy("statut", array("libelle" => $data['statut']));
            } else {
                $statut = $published;
            }

            $jeu = new Jeu();
            $jeu
                ->setLibelle(trim($data['libelle']))
                ->setAgeMin(isset($data['age_min']) ? $data['age_min'] : null)
                ->setJoueurMin(isset($data['joueur_min']) ? $data['joueur_min'] : null)
                ->setJoueurMax(isset($data['joueur_max']) ? $data['joueur_max'] : null)
                ->setStatut($statut)
            ;

            $manager->persist($jeu);
        }

        $manager->flush();
    }

    /**
     * @Then /I am on game "([^"]*)"$/
     */
    public function iAmOnGame($jeuLibelle)
    {
        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));
        $this->getSession()->visit($this->baseUrl.'/jeu/'.$jeu->getId().'/'.$jeu->getSlug());
    }

    /**
     * @Then /I am on edition page of the game "([^"]*)"$/
     */
    public function iAmOnEditionPageOfTheGame($jeuLibelle)
    {
        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));
        $this->getSession()->visit($this->baseUrl.'/jeu/'.$jeu->getId().'/edit');
    }

    /**
     * @Then /I should be on game "([^"]*)"$/
     */
    public function iShouldBeOnGame($jeuLibelle)
    {
        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));
        $this->assertSession()->addressEquals($this->baseUrl.'/jeu/'.$jeu->getId().'/'.$jeu->getSlug());
    }

    /**
     * @Then /I should be on edition page of the game "([^"]*)"$/
     */
    public function iShouldBeOnEditionPageOfTheGame($jeuLibelle)
    {
        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));
        $this->assertSession()->addressEquals($this->baseUrl.'/jeu/'.$jeu->getId().'/edit');
    }

    /**
     * @Then /I am on game list page$/
     */
    public function iAmOnGameList()
    {
        $this->getSession()->visit("/jeu");
    }
} 