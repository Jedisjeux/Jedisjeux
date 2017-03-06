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
class LoadReviewArticlesCommand extends LoadArticlesCommand
{
    const BATCH_SIZE = 20;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:review-articles:load')
            ->setDescription('Load review articles')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads all review articles.
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

        foreach ($this->getReviewArticles() as $key => $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> review article", $data['title']));
            $article = $this->createOrReplaceArticle($data);

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = $this->getContainer()->get('sylius.repository.taxon')->findOneBy(['code' => Taxon::CODE_REVIEW_ARTICLE]);
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

        $output->writeln(sprintf("<info>%s</info>", "Review articles have been successfully loaded."));

    }

    /**
     * @return array
     */
    protected function getReviewArticles()
    {
        $query = <<<EOM
SELECT
  concat('review-article-', test.game_id)                            AS code,
  test.date                                                          AS publishedAt,
  product.id                                                         AS product_id,
  productTranslation.name                                            AS product_name,
  game.couverture                                                    AS mainImage,
  concat('Critique de ', productTranslation.name)                    AS title,
  concat('critique-', productTranslation.slug, '-ra-', test.game_id) AS name,
  topic.id                                                           AS topic_id,
  customer.id                                                        AS author_id,
  test.valid                                                         AS publishable,
  review_article_view.view_count                                     AS view_count
FROM jedisjeux.jdj_tests test
  INNER JOIN jedisjeux.jdj_v_review_article_view_count AS review_article_view
    ON review_article_view.id = test.game_id
  INNER JOIN jedisjeux.jdj_game game
    ON game.id = test.game_id
  INNER JOIN sylius_product_variant productVariant
    ON productVariant.code = concat('game-', test.game_id)
  INNER JOIN sylius_product product
    ON product.id = productVariant.product_id
  INNER JOIN sylius_product_translation productTranslation
    ON productTranslation.translatable_id = product.id
       AND locale = 'fr'
  LEFT JOIN jdj_topic topic
    ON topic.code = concat('topic-', test.topic_id)
  INNER JOIN sylius_customer customer
    ON customer.code = concat('user-', test.user_id)
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }
}
