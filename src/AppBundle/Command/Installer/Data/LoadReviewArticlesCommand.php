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
  article.id              AS article_id,
  article.title           AS article_title,
  article.code            AS article_code,
  test.materiel           AS material,
  test.regle              AS rules,
  test.duree_vie          AS lifetime,
  test.conseil            AS advice,
  test.note_materiel / 2  AS materialRating,
  test.note_regle / 2     AS rulesRating,
  test.note_duree_vie / 2 AS lifetimeRating,
  material_image.img_nom  AS material_image_path,
  rules_image.img_nom     AS rules_image_path,
  lifetime_image.img_nom  AS lifetime_image_path
FROM jedisjeux.jdj_tests test
  INNER JOIN jdj_article article
    ON article.code = concat('review-article-', test.game_id)
  LEFT JOIN jedisjeux.jdj_images_elements material_img
    ON material_img.elem_type = 'test'
       AND material_img.elem_id = test.game_id
       AND material_img.ordre = 1
  LEFT JOIN jedisjeux.jdj_images material_image
    ON material_image.img_id = material_img.img_id
  LEFT JOIN jedisjeux.jdj_images_elements rules_img
    ON rules_img.elem_type = 'test'
       AND rules_img.elem_id = test.game_id
       AND rules_img.ordre = 2
  LEFT JOIN jedisjeux.jdj_images rules_image
    ON rules_image.img_id = rules_img.img_id
  LEFT JOIN jedisjeux.jdj_images_elements lifetime_img
    ON lifetime_img.elem_type = 'test'
       AND lifetime_img.elem_id = test.game_id
       AND lifetime_img.ordre = 3
  LEFT JOIN jedisjeux.jdj_images lifetime_image
    ON lifetime_image.img_id = lifetime_img.img_id
;
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }
}
