<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Article;
use AppBundle\Entity\Block;
use AppBundle\Entity\BlockImage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadBlocksOfNewsCommand extends LoadBlocksOfArticlesCommand
{
    const BATCH_SIZE = 20;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:blocks-of-news:load')
            ->setDescription('Load blocks of all news')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads blocks of all news.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getReviewArticlesBlocks() as $key => $data) {
            $output->writeln(sprintf("Loading block <comment>%s</comment> of <comment>%s</comment> article", $data['code'], $data['article_title']));

            /** @var Article $article */
            $article = $this->getContainer()->get('app.repository.article')->find($data['article_id']);
            $block = $this->createOrReplaceBlock($data);
            $article
                ->addBlock($block);

            $this->getManager()->persist($block);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Blocks of all articles have been successfully loaded."));
    }

    /**
     * @return array
     */
    protected function getReviewArticlesBlocks()
    {
        $query = <<<EOM
SELECT
  concat('block-news-', old.news_id) AS code,
  old.text                           AS body,
  article.id                         AS article_id,
  article.title                      AS article_title,
  'left'                             AS image_position,
  NULL                               AS title,
  old.photo                          AS image,
  NULL                               AS image_label,
  NULL                               AS class
FROM jedisjeux.jdj_news old
  INNER JOIN jdj_article article
    ON article.code = concat('news-', old.news_id)
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }
}
