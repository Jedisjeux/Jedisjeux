<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/03/2016
 * Time: 09:38
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadReviewsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:ratings:load')
            ->setDescription('Load ratings');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getReviews() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> review", $data['username']));
        }
    }

    /**
     * @return array
     */
    protected function getReviews()
    {
        $query = <<<EOM
select      old.id,
            user.id,
            jeu.id,
            old.note/2,
            old.date,
            old.date
from        jedisjeux.jdj_avis old
inner join  jdj_jeu jeu
                on jeu.id = old.game_id
inner join  fos_user user
                on user.id = old.user_id
EOM;
        return $this->getDatabaseConnection()->fetchAll($query);

    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
