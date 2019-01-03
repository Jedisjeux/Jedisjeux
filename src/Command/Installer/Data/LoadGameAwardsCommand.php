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

class LoadGameAwardsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var FactoryInterface
     */
    private $gameAwardFactory;

    /**
     * @var RepositoryInterface
     */
    private $gameAwardRepository;

    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:load-game-awards';

    /**
     * @param EntityManagerInterface $manager
     * @param FactoryInterface $gameAwardFactory
     * @param RepositoryInterface $gameAwardRepository
     */
    public function __construct(
        EntityManagerInterface $manager,
        FactoryInterface $gameAwardFactory,
        RepositoryInterface $gameAwardRepository
    ) {
        $this->manager = $manager;
        $this->gameAwardFactory = $gameAwardFactory;
        $this->gameAwardRepository = $gameAwardRepository;

        parent::__construct();
    }


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Load all game awards.')
            ->setHelp('This command allows you to load game awards');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        foreach ($this->getGameAwards() as $data) {
            $output->writeln(sprintf('Loading <info>%s</info>', $data['name']));
            $this->createGameAward($data);
        }

        $this->manager->flush();
    }

    /**
     * @param array $data
     */
    private function createGameAward(array $data): void
    {
        /** @var GameAward $gameAward */
        $gameAward = $this->gameAwardRepository->findOneBy(['name' => $data['name']]);

        if (null === $gameAward) {
            $gameAward = $this->gameAwardFactory->createNew();
            $this->manager->persist($gameAward);
        }

        $gameAward->setName($data['name']);
        $gameAward->setDescription(sprintf('<p>%s</p>', $data['description']));
    }

    /**
     * @return array
     */
    private function getGameAwards(): array
    {
        $sql = <<<EOM
select p.nom as name, p.description
from jedisjeux.jdj_prix p
EOM;

        return $this->manager->getConnection()->fetchAll($sql);
    }
}
