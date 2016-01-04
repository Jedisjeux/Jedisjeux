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
        /** @var Jeu $game */
        $game = null;
        foreach ($this->getRows() as $data) {
            if (null === $game or $data['gameId'] !== $game->getId()) {
                $game = $this->getEntityManager()->getRepository('JDJJeuBundle:Jeu')->find($data['gameId']);
                $game->setAuteurs(new ArrayCollection());
                $game->setEditeurs(new ArrayCollection());
                $game->setIllustrateurs(new ArrayCollection());
                $this->getEntityManager()->flush();
            }

            /** @var Personne $person */
            $person = $this->getEntityManager()->getRepository('JDJLudographieBundle:Personne')->find($data['personId']);

            if ('auteur' === $data['job']) {
                $game->addAuteur($person);
            } elseif ('illustrateur' === $data['job']) {
                $game->addIllustrateur($person);
            } elseif ('editeur' === $data['job']) {
                $game->addEditeur($person);
            }

            $this->getEntityManager()->persist($game);
            $this->getEntityManager()->flush();
            $this->getEntityManager()->clear();
        }
    }

    /**
     * @inheritdoc
     */
    public function getRows()
    {
        $query = <<<EOM
select      old.id_game as gameId,
            old.id_personne as personId,
            old.type_relation as job
from        jedisjeux.jdj_personne_game old
inner join  jdj_jeu jeu on jeu.id = old.id_game
inner JOIN  jdj_personne person on person.id = old.id_personne
EOM;
        $rows = $this->getDatabaseConnection()->fetchAll($query);

        return $rows;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}