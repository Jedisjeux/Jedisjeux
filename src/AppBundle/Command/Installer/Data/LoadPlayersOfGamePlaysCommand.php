<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/04/16
 * Time: 14:25
 */

namespace AppBundle\Command\Installer\Data;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadPlayersOfGamePlaysCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:players-of-game-plays:load')
            ->setDescription('Loading players of game plays');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));
        $this->deletePlayers();
        $this->insertPlayers();
    }

    protected function deletePlayers()
    {
        $queryBuiler = $this->getManager()->createQuery('delete from AppBundle:Player');
        $queryBuiler->execute();
    }

    protected function insertPlayers()
    {
        $query = <<<EOM
insert into jdj_player(game_play_id, name, score, customer_id)
select      gamePlay.id,
            case
                when user.customer_id is null then old.joueur
            else null
            end as name,
            old.score, user.customer_id
from        jedisjeux.jdj_scores old
  inner join  jdj_game_play gamePlay
    on gamePlay.code = concat('game-play-', old.partie_id)
  left join sylius_user user
              on convert(user.username USING utf8) like convert(old.joueur USING utf8);
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
