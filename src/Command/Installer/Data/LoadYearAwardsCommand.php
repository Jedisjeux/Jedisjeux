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

use App\Entity\GameAward;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadYearAwardsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:load-year-awards';

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;

        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Load all year awards.')
            ->setHelp('This command allows you to load game awards');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $this->deleteExistingGameAwards();
        $this->addGameAwards();
    }

    private function deleteExistingGameAwards()
    {
        $this->manager->createQueryBuilder()->delete();
    }

    private function addGameAwards()
    {
        $query = <<<EOM
insert into jdj_year_award(award_id, product_id, year)
select award.id as award_id,
       variant.product_id,
       pg.annee as year
from jedisjeux.jdj_prix_game pg
       inner join jedisjeux.jdj_prix p on p.id_prix = pg.id_prix
       inner join sylius_product_variant variant on variant.code = concat('game-', pg.game_id)
       inner join jdj_game_award award on award.name = p.nom COLLATE utf8_unicode_ci
where pg.rang = 1;
EOM;

        $this->manager->getConnection()->executeQuery($query);
    }
}
