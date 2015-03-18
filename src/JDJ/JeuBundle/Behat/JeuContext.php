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

        $statutValide = $this->findOneBy("statut", array("libelle" => 'validé'));

        foreach ($table->getHash() as $data) {

            /** @var Statut $statut */
            if (isset($data['statut'])) {
                $statut = $this->findOneBy("statut", array("libelle" => $data['statut']));
            } else {
                $statut = $statutValide;
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
        //file_put_contents(__DIR__.'/../../../../web/behat/'.$jeu->getSlug().'.html', $this->getSession()->getPage()->getContent());
    }

    /**
     * @Then /I am on game list page$/
     */
    public function iAmOnGameList()
    {
        $this->getSession()->visit("/jeu");
        //file_put_contents(__DIR__.'/../../../../web/behat/game-list.html', $this->getSession()->getPage()->getContent());
    }
} 