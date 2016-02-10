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
        $queries = array();

        $query = <<<EOM
        delete from jdj_user_review
EOM;

        $queries[] = $query;

        $query = <<<EOM
        delete from jdj_jeu_note
EOM;

        $queries[] = $query;

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

        $queries[] = $query;

        $query = <<<EOM
insert into jdj_user_review (
       id,
       libelle,
       body,
       createdAt,
       updatedAt,
       jeuNote_id
)
select      old.id,
      old.accroche,
      concat ('<p>', old.avis, '</p>'),
      date,
      date,
      jeuNote.id
from        jedisjeux.jdj_avis old
      inner join  jdj_jeu_note jeuNote
             on jeuNote.id = old.id
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