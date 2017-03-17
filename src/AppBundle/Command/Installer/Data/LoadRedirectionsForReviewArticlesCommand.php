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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadRedirectionsForReviewArticlesCommand extends AbstractLoadRedirectionsCommand
{
    const BATCH_SIZE = 20;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:redirections-for-review-articles:load')
            ->setDescription('Load all redirections for review articles');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getReviewArticles() as $data) {
            $output->writeln(sprintf("Loading redirection for <comment>%s</comment> page", $data['source']));

            $redirect = $this->createOrReplaceRedirection($data);
            $this->getManager()->persist($redirect);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush($redirect); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Redirects for pages have been successfully loaded."));
    }

    /**
     * @return array
     */
    protected function getReviewArticles()
    {
        $query = <<<EOM
SELECT
  concat('/', replace(oldGame.mot_cle, ' ', '-'), '-t-', old.game_id, '.html') AS source,
  concat('/article/', article.slug)                                            AS destination
FROM jedisjeux.jdj_tests old
  INNER JOIN jedisjeux.jdj_game oldGame
    ON oldGame.id = old.game_id
  INNER JOIN jdj_article article
    ON article.code = concat('review-article-', old.game_id)
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }
}
