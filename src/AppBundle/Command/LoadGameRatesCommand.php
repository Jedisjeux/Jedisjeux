<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 10/02/2016
 * Time: 13:28
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadGameRatesCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:game-rates:load')
            ->setDescription('Load rates of all games');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queries = array();

        $query = <<<EOM
        delete from jdj_game_rate
EOM;

        $queries[] = $query;

        $query = <<<EOM
        delete from jdj_game_review
EOM;

        $queries[] = $query;

        $query = <<<EOM
insert into jdj_game_rate (
            id,
            createdBy_id,
            game_id,
            value,
            createdAt,
            updatedAt
)
select      old.id,
            user.id,
            jeu.id,
            old.note,
            old.date,
            old.date
from        jedisjeux.jdj_avis old
inner join  jdj_jeu jeu
                on jeu.id = old.game_id
inner join  fos_user user
                on user.id = old.user_id
EOM;

        $queries[] = $query;

        $query = <<<EOM
insert into jdj_game_review (
       id,
       title,
       body,
       createdAt,
       updatedAt,
       rate_id
)
select      old.id,
      old.accroche,
      concat ('<p>', replace(old.avis, '\n', '</p><p>') , '</p>'),
      date,
      date,
      rate.id
from        jedisjeux.jdj_avis old
      inner join  jdj_game_rate rate
             on rate.id = old.id
where       old.avis <> ''
EOM;
        $queries[] = $query;

        foreach ($queries as $query) {
            $this->getDatabaseConnection()->executeQuery($query);
        }

    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}