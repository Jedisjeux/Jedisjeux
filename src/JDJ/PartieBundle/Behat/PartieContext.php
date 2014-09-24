<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 22/09/2014
 * Time: 11:55
 */

namespace JDJ\PartieBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\PartieBundle\Entity\Partie;

/**
 * Class PartieContext
 * @package JDJ\PartieBundle\Behat
 */
class PartieContext extends DefaultContext
{
    /**
     * @Given /^game "([^""]*)" has following parties:$/
     */
    public function gameHasFollowingParties($jeuLibelle, TableNode $table){
        $manager = $this->getEntityManager();

        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array(
            'libelle' => $jeuLibelle,
        ));

        foreach ($table->getHash() as $data) {

            $user = $this->findOneBy("user", array(
                "username" => $data['username']
            ));

            $partie = new Partie();
            $partie
                ->setAuthor($user)
                ->setJeu($jeu)
                ->setPlayedAt(\DateTime::createFromFormat("d/m/Y", $data['playedAt']))
            ;

            $manager->persist($partie);
            $jeu->addParty($partie);
        }

        $manager->flush();

    }


    /**
     * @Then /I am on partie creation of "([^"]*)"$/
     */
    public function iAmOnPartieCreation($jeuLibelle)
    {
        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));
        $this->getSession()->visit("/jeu/".$jeu->getId()."/partie/new");
        file_put_contents(__DIR__.'/../../../../web/behat/'.$jeu->getSlug().'-partie-new.html', $this->getSession()->getPage()->getContent());
    }

    /**
     * @Then /I should be on partie creation of "([^"]*)"$/
     */
    public function iShouldBeOnPartieCreation($jeuLibelle)
    {
        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));
        file_put_contents(__DIR__.'/../../../../web/behat/'.$jeu->getSlug().'-partie-new.html', $this->getSession()->getPage()->getContent());
        $this->assertSession()->addressEquals("/jeu/".$jeu->getId()."/partie/new");
        $this->assertStatusCodeEquals(200);
    }



    /**
     * @Then /I should be on joueur creation page$/
     */
    public function iShouldBeOnJoueurCreationPage()
    {
        file_put_contents(__DIR__.'/../../../../web/behat/partie-joueur-new.html', $this->getSession()->getPage()->getContent());
        $this->assertSession()->addressMatches('/\/partie\/\d+\/joueur\/new/');
        $this->assertStatusCodeEquals(200);
    }

    /**
     * @Then /I should be on joueur edition page$/
     */
    public function iShouldBeOnJoueurEditionPage()
    {
        file_put_contents(__DIR__.'/../../../../web/behat/partie-joueur-new.html', $this->getSession()->getPage()->getContent());
        $this->assertSession()->addressMatches('/\/partie\/joueur\/\d+\/edit/');
        $this->assertStatusCodeEquals(200);
    }

    /**
     * @Then /I should be on partie show page$/
     */
    public function iShouldBeOnPartieShowPage()
    {
        file_put_contents(__DIR__.'/../../../../web/behat/partie-show.html', $this->getSession()->getPage()->getContent());
        $this->assertSession()->addressMatches('/\/partie\/\d+\/jeu\/(.+)/');
        $this->assertStatusCodeEquals(200);
    }
} 