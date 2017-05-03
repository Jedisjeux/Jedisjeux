<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\FeedDump;

use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Eko\FeedBundle\Service\FeedDumpService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleFeedDumpCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:article-feed:dump')
            ->setDescription('Generate (dump) article feed in an XML file.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command generate article feed in an XML file.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $this->getDescription()));

        $filename = "jedisjeux.xml";
        $articles = $this->findPublishedArticles();

        $feedDumpService = $this->getFeedDumpService();
        $feedDumpService
            ->setName('article')
            ->setFilename($filename)
            ->setFormat('rss')
            ->setItems($articles)
            ->setRootDir($this->getContainer()->get('kernel')->getRootDir().'/../');

        $feedDumpService->dump();

        $output->writeln('<comment>done!</comment>');
        $output->writeln(sprintf('<info>Article feed has been dumped and located in "%s"</info>', $feedDumpService->getRootDir() . $filename));
    }

    /**
     * @return object|FeedDumpService
     */
    protected function getFeedDumpService()
    {
        return $this->getContainer()->get('eko_feed.feed.dump');
    }

    /**
     * @return object|ArticleRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('app.repository.article');
    }

    /**
     * @return array
     */
    protected function findPublishedArticles()
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder('o');
        $queryBuilder
            ->andWhere('o.status = :published')
            ->orderBy('o.publishStartDate', 'desc')
            ->setMaxResults(20)
            ->setParameter('published', Article::STATUS_PUBLISHED);

        return $queryBuilder->getQuery()->getResult();
    }
}
