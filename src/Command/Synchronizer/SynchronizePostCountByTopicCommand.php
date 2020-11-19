<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Command\Synchronizer;

use App\Entity\Topic;
use App\Repository\TopicRepository;
use App\Updater\PostCountByTopicUpdater;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SynchronizePostCountByTopicCommand extends Command
{
    protected static $defaultName = 'app:synchronize-post-count-by-topic';

    /** @var PostCountByTopicUpdater */
    private $postCountByTopicUpdater;

    /** @var TopicRepository */
    private $topicRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        PostCountByTopicUpdater $postCountByTopicUpdater,
        TopicRepository $topicRepository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();

        $this->postCountByTopicUpdater = $postCountByTopicUpdater;
        $this->topicRepository = $topicRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Synchronizing post count by topic...');
        $this->recalculate();
        $output->writeln('<info>Post count by topic synchronized successfully.</info>');
    }

    private function recalculate(): void
    {
        foreach ($this->topicRepository->createQueryBuilder('o')->getQuery()->iterate() as $row) {
            /** @var Topic $topic */
            $topic = $row[0];

            $this->postCountByTopicUpdater->update($topic);
            $this->entityManager->flush();
            $this->entityManager->clear();
        }
    }
}
