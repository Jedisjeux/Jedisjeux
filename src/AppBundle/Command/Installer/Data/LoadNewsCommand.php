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
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadNewsCommand extends LoadArticlesCommand
{
    const BATCH_SIZE = 20;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:news:load')
            ->setDescription('Load news')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads all news.
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

        foreach ($this->getNews() as $key => $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> news", $data['title']));
            $article = $this->createOrReplaceArticle($data);

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = $this->getContainer()->get('sylius.repository.taxon')->findOneBy(['code' => Taxon::CODE_NEWS]);
            $article->setMainTaxon($mainTaxon);

            if (null !== $data['product_id']) {
                /** @var ProductInterface $product */
                $product = $this->getContainer()->get('sylius.repository.product')->find($data['product_id']);

                $article
                    ->setProduct($product);
            }

            if (null !== $data['topic_id']) {
                /** @var Topic $topic */
                $topic = $this->getContainer()->get('app.repository.topic')->find($data['topic_id']);

                $article
                    ->setTopic($topic);
            }

            /** @var CustomerInterface $author */
            $author = $this->getContainer()->get('sylius.repository.customer')->find($data['author_id']);
            $article
                ->setAuthor($author);

            $this->getManager()->persist($article);

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;
        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "News have been successfully loaded."));

    }

    /**
     * @return array
     */
    protected function getNews()
    {
        $query = <<<EOM
SELECT
  concat('news-', old.news_id)                                   AS code,
  old.titre                                                      AS title,
  concat(replace(old.titre_clean, ' ', '-'), '-n-', old.news_id) AS name,
  old.date                                                       AS publishedAt,
  old.text                                                       AS body,
  old.photo                                                      AS mainImage,
  customer.id                                                    AS author_id,
  old.photo                                                      AS mainImage,
  product.id                                                     AS product_id,
  CASE WHEN old.news_id NOT IN (371, 3650)
    THEN topic.id
  ELSE NULL END                                                  AS topic_id,
  old.nb_clicks                                                  AS view_count,
  old.valid                                                      AS publishable,
  CASE old.valid
  WHEN 1
    THEN 'published'
  WHEN 5
    THEN 'need-a-review'
  WHEN 3
    THEN 'ready-to-publish'
  ELSE 'new'
  END                                                            AS status
FROM jedisjeux.jdj_news old
  INNER JOIN sylius_customer customer
    ON customer.code = concat('user-', old.user_id)
  LEFT JOIN sylius_product_variant productVariant
    ON productVariant.code = concat('game-', old.game_id)
  LEFT JOIN sylius_product product
    ON product.id = productVariant.product_id
  LEFT JOIN jdj_topic topic
    ON topic.code = concat('topic-', old.topic_id)
WHERE old.valid >= 0
      AND old.type_lien IN (0, 1)
ORDER BY old.date DESC
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }
}
