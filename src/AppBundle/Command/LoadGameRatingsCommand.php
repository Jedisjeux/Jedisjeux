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
class LoadGameRatingsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:game-ratings:load')
            ->setDescription('Load ratings of all games');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = <<<EOM
insert into jdj_jeu_note (
            id,
            author_id,
            jeu_id,
            note_id,
            createdAt,
            updatedAt
)
select      old.id,
            user.id,
            jeu.id,
            note.id,
            old.date,
            null
from        jedisjeux.jdj_avis old
inner join  jdj_jeu jeu
                on jeu.id = old.game_id
inner join  fos_user user
                on user.id = old.user_id
inner join  jdj_note note
                on note.id = old.note
EOM;

        $this->getDatabaseConnection()->executeQuery($query);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}