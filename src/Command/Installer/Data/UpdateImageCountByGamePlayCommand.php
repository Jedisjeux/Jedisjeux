<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\Installer\Data;

use App\Entity\GamePlay;
use App\Updater\ImageCountByGamePlayUpdater;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UpdateImageCountByGamePlayCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:images:count-by-game-play')
            ->setDescription('Update image count by game play.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command updates image count by game play.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $this->calculateImageCountByGamePlays();

        $output->writeln(sprintf('<info>%s</info>', 'Image count by game play have been successfully updated.'));
    }

    protected function calculateImageCountByGamePlays()
    {
        foreach ($this->createQueryBuilder()->getQuery()->iterate() as $row) {
            /** @var GamePlay $gamePlay */
            $gamePlay = $row[0];

            $this->getImageCountByGamePlayUpdater()->update($gamePlay);
            $this->getManager()->flush($gamePlay);
            $this->getManager()->detach($gamePlay);
            $this->getManager()->clear();
        }
    }

    /**
     * Creates a new QueryBuilder instance that is prepopulated for this entity name.
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->getGamePlayRepository()->createQueryBuilder('o');
    }

    /**
     * @return ImageCountByGamePlayUpdater|object
     */
    protected function getImageCountByGamePlayUpdater(): ImageCountByGamePlayUpdater
    {
        return $this->getContainer()->get('App\Updater\ImageCountByGamePlayUpdater');
    }

    /**
     * @return RepositoryInterface|object
     */
    protected function getGamePlayRepository(): RepositoryInterface
    {
        return $this->getContainer()->get('app.repository.game_play');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
