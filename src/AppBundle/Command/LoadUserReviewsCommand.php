<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 10/02/2016
 * Time: 13:36
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadUserReviewsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:user-reviews:load')
            ->setDescription('Load user reviews of all games');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $query = <<<EOM
insert into jdj_user_review (
            id,
            libelle,
            body,
            createdAt,
            jeuNote_id
)
select      old.id,
            old.accroche,
            concat ('<p>', old.avis, '</p>'),
            date,
            jeuNote.id
from        jedisjeux.jdj_avis old
inner join  jdj_jeu_note jeuNote
                on jeuNote.id = old.id
where       old.avis <> ''
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