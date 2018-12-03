<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command\FeedDump;

use App\Feed\ArticleFeedDump;
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

        $articleFeedDump = $this->getArticleFeedDump();
        $articleFeedDump->dump();

        $output->writeln('<comment>done!</comment>');
        $output->writeln(sprintf('<info>Article feed has been dumped and located in "%s"</info>', $articleFeedDump->getRootDir().$articleFeedDump->getFileName()));
    }

    /**
     * @return object|ArticleFeedDump
     */
    protected function getArticleFeedDump()
    {
        return $this->getContainer()->get('app.feed.dump.article');
    }
}
