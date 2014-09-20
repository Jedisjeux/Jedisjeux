<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 18/09/2014
 * Time: 23:58
 */

namespace JDJ\JeuBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Entity\Theme;

class ThemeContext extends DefaultContext
{

    /**
     * @Given /^game "([^""]*)" has following themes:$/
     */
    public function gameHasFollowingThemes($jeuLibelle, TableNode $themesTable)
    {
        $manager = $this->getEntityManager();

        /** @var Jeu $jeu */
        $jeu = $this->findOneBy("jeu", array("libelle" => $jeuLibelle));

        foreach ($themesTable->getRows() as $node) {

            $themeLibelle = $node[0];

            $theme = $this
                ->getRepository("theme")
                ->findOneBy(array("libelle" => trim($themeLibelle)))
            ;

            if (null === $theme) {
                $theme = new Theme();
                $theme
                    ->setLibelle(trim($themeLibelle))
                ;
            }
            $jeu->addTheme($theme);

        }

        $manager->persist($jeu);
        $manager->flush();
    }


    /**
     * @Then /I am on theme "([^"]*)"$/
     */
    public function iAmOnTheme($themeLibelle)
    {
        /** @var Theme $theme */
        $theme = $this->findOneBy("theme", array("libelle" => $themeLibelle));
        $this->getSession()->visit("/jeu/theme/".$theme->getId()."/".$theme->getSlug());
    }

    /**
     * @Then /I should be on theme "([^"]*)"$/
     */
    public function iShouldBeOnTheme($mecanismeLibelle)
    {
        /** @var Theme $theme */
        $theme = $this->findOneBy("theme", array("libelle" => $mecanismeLibelle));
        $this->assertSession()->addressEquals("/jeu/theme/".$theme->getId()."/".$theme->getSlug());
        $this->assertStatusCodeEquals(200);
    }
} 