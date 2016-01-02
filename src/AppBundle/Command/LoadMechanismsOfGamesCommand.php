<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/12/2015
 * Time: 16:32
 */

namespace AppBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JDJ\JeuBundle\Entity\Jeu;
use JDJ\JeuBundle\Entity\Mechanism;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadMechanismsOfGamesCommand extends ContainerAwareCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:mechanisms-of-games:load')
            ->setDescription('Load mechanisms of games');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load mechanisms of games</comment>");
        $game = null;
        foreach ($this->getRows() as $data) {

            if (null === $game or $data['gameId'] !== $game->getId()) {
                /** @var Jeu $game */
                $game = $this->getEntityManager()->getRepository('JDJJeuBundle:Jeu')->find($data['gameId']);
                $game->setMechanisms(new ArrayCollection());
                $this->getEntityManager()->flush();
            }

            /** @var Mechanism $mechanism */
            $mechanism = $this->getEntityManager()->getRepository('JDJJeuBundle:Mechanism')->find($data['mechanismId']);

            $game->addMechanism($mechanism);
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
select      old.jeux_id as gameId,
            old.mecanisme_id as mechanismId
from        jedisjeux.jdj_mecanismelieur old
inner join  jdj_jeu jeu on jeu.id = old.jeux_id
inner JOIN  jdj_mechanism mechanism on mechanism.id = old.mecanisme_id
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