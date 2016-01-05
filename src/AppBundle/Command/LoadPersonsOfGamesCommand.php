<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 04/01/2016
 * Time: 13:51
 */

namespace AppBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\LudographieBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadPersonsOfGamesCommand extends ContainerAwareCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:persons-of-games:load')
            ->setDescription('Load persons of games');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load persons of games</comment>");

        $this->loadAuthorsOfGames();
        $this->loadEditorsOfGames();
        $this->loadIllustratorsOfGames();
    }

    protected function loadAuthorsOfGames()
    {
        $query = <<<EOM
        delete from jdj_auteur_jeu
EOM;
        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_auteur_jeu (
            personne_id,
            jeu_id
)
select      personne.id,
            jeu.id
from        jedisjeux.jdj_personne_game old
inner join  jdj_jeu jeu
                on jeu.id = old.id_game
inner join  jdj_personne personne
                on personne.id = old.id_personne
where       old.type_relation = 'auteur'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    protected function loadEditorsOfGames()
    {
        $query = <<<EOM
        delete from jdj_editeur_jeu
EOM;
        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_editeur_jeu (
            personne_id,
            jeu_id
)
select      personne.id,
            jeu.id
from        jedisjeux.jdj_personne_game old
inner join  jdj_jeu jeu
                on jeu.id = old.id_game
inner join  jdj_personne personne
                on personne.id = old.id_personne
where       old.type_relation = 'editeur'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    protected function loadIllustratorsOfGames()
    {
        $query = <<<EOM
        delete from jdj_illustrateur_jeu
EOM;
        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into jdj_illustrateur_jeu (
            personne_id,
            jeu_id
)
select      personne.id,
            jeu.id
from        jedisjeux.jdj_personne_game old
inner join  jdj_jeu jeu
                on jeu.id = old.id_game
inner join  jdj_personne personne
                on personne.id = old.id_personne
where       old.type_relation = 'illustrateur'
EOM;
        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}