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
use JDJ\JeuBundle\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadThemesOfGamesCommand extends ContainerAwareCommand
{
    protected $writeEntityInOutput = false;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:themes-of-games:load')
            ->setDescription('Load themes of games');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln("<comment>Load themes of games</comment>");
        /** @var Jeu $game */
        $game = null;
        foreach ($this->getRows() as $data) {

            if (null === $game or $data['gameId'] !== $game->getId()) {
                $game = $this->getEntityManager()->getRepository('JDJJeuBundle:Jeu')->find($data['gameId']);
                $game->setThemes(new ArrayCollection());
                $this->getEntityManager()->flush();
            }

            /** @var Theme $theme */
            $theme = $this->getEntityManager()->getRepository('JDJJeuBundle:Theme')->find($data['themeId']);

            $game->addTheme($theme);
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
            old.theme_id as themeId
from        jedisjeux.jdj_themelieur old
inner join  jdj_jeu jeu on jeu.id = old.jeux_id
inner JOIN  jdj_theme theme on theme.id = old.theme_id
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