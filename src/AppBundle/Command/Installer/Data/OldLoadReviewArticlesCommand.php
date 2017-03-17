<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Command\LogMemoryUsageTrait;
use AppBundle\Document\ArticleContent;
use AppBundle\Document\BlockquoteBlock;
use AppBundle\Document\ImagineBlock;
use AppBundle\Document\SingleImageBlock;
use AppBundle\Entity\Article;
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class OldLoadReviewArticlesCommand extends AbstractLoadDocumentCommand
{
    use LogMemoryUsageTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:old-review-articles:load')
            ->setDescription('Load review articles')
            ->addOption('no-update')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Set limit of review-articles to import');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_collect_cycles();
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getTests() as $key => $data) {
            $output->writeln(sprintf("Loading test of <comment>%s</comment> product", $data['product_name']));
            $this->logMemoryUsage($output);

            $article = $this->createOrReplaceArticle($data);

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = $this->getTaxonRepository()->findOneBy(['code' => Taxon::CODE_REVIEW_ARTICLE]);
            $article->setMainTaxon($mainTaxon);

            $articleDocument = $article->getDocument();
            $this->getDocumentManager()->persist($articleDocument);
            $this->getDocumentManager()->flush();

            $this->createOrReplaceIntroductionBlock($articleDocument, $data);
            $blocks = [
                [
                    'id' => $data['name'] . '0',
                    'body' => $data['material'],
                    'image_position' => SingleImageBlock::POSITION_LEFT,
                    'image_label' => null,
                    'image' => $data['material_image_path'],
                    'title' => 'Matériel',
                    'class' => null,
                ], [
                    'id' => $data['name'] . '1',
                    'body' => $data['rules'],
                    'image_position' => SingleImageBlock::POSITION_RIGHT,
                    'image_label' => null,
                    'image' => $data['rules_image_path'],
                    'title' => 'Règles',
                    'class' => null,
                ], [
                    'id' => $data['name'] . '2',
                    'body' => $data['lifetime'],
                    'image_position' => SingleImageBlock::POSITION_TOP,
                    'image_label' => null,
                    'image' => $data['lifetime_image_path'],
                    'title' => 'Durée de vie',
                    'class' => null,
                ]
            ];

            if (!empty($data['advice'])) {
                $blocks[] = [
                    'id' => $data['name'] . '3',
                    'body' => $data['advice'],
                    'image_position' => SingleImageBlock::POSITION_TOP,
                    'image_label' => null,
                    'image' => null,
                    'title' => 'Le conseil de Jedisjeux',
                    'class' => 'well',
                ];
            }

            $this->populateBlocks($articleDocument, $blocks);

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

            if (null !== $data['author_id']) {
                /** @var CustomerInterface $author */
                $author = $this->getContainer()->get('sylius.repository.customer')->find($data['author_id']);
                $article
                    ->setAuthor($author);
            }

            $article
                ->setMaterialRating($data['materialRating'])
                ->setRulesRating($data['rulesRating'])
                ->setLifetimeRating($data['lifetimeRating']);

            $this->getDocumentManager()->persist($articleDocument);
            $this->getDocumentManager()->flush();

            $this->getManager()->persist($article);
            $this->getManager()->flush();
            $this->getManager()->clear();

            $this->getDocumentManager()->detach($articleDocument);
            $this->getDocumentManager()->clear();

            if ($key > 0 and $key%10 === 0) {
                $this->clearDoctrineCache();
            }
        }

        $this->clearDoctrineCache();
        $stats = $this->getTotalOfItemsLoaded();
        $this->showTotalOfItemsLoaded($stats['itemCount'], $stats['totalCount']);
    }

    /**
     * @param array $data
     *
     * @return Article
     */
    protected function createOrReplaceArticle(array $data)
    {
        $article = $this->findArticle($data['name']);

        if (null === $article) {
            $article = $this->getFactory()->createNew();
        }

        $article
            ->setCode('review-article-'.$data['id'])
            ->setViewCount($data['view_count'])
            ->setStatus($data['publishable'] ? Article::STATUS_PUBLISHED : Article::STATUS_NEW);
        $articleDocument = $article->getDocument();

        if (null !== $data['main_image']) {
            $mainImage = $articleDocument->getMainImage();

            if (null === $mainImage) {
                /** @var ImagineBlock $mainImage */
                $mainImage = $this->getContainer()->get('app.factory.imagine_block')->createNew();
            }


            $image = new Image();
            $image->setFileContent(file_get_contents($this->getImageOriginalPath($data['main_image'])));

            $mainImage
                ->setParentDocument($articleDocument)
                ->setImage($image);

            $articleDocument
                ->setMainImage($mainImage);
        }

        $articleDocument->setName($data['name']);
        $articleDocument->setTitle($data['title']);
        $articleDocument->setPublishable((bool)$data['publishable']);
        $articleDocument->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']));

        return $article;
    }

    /**
     * @param ArticleContent $page
     * @param array $data
     *
     * @return BlockquoteBlock|null|object
     */
    protected function createOrReplaceIntroductionBlock(ArticleContent $page, array $data)
    {
        if (!isset($data['introduction'])) {
            return null;
        }

        $name = 'block0';
        $id = $page->getId().'/'.$name;

        /** @var BlockquoteBlock $block */
        $block = $this
            ->getContainer()->get('app.repository.blockquote_block')
            ->find($id);

        if (null === $block) {
            $block = $this->getContainer()->get('app.factory.blockquote_block')->createNew();
            $block->setName($name);
        }

        $block
            ->setBody(sprintf('<p>%s</p>', $data['introduction']))
            ->setName($name)
            ->setPublishable(true);

        $page->addBlock($block);

        return $block;
    }

    /**
     * @return array
     */
    protected function getTests()
    {
        $query = <<<EOM
SELECT
  test.game_id                                                       AS id,
  test.date                                                          AS publishedAt,
  product.id                                                         AS product_id,
  productTranslation.name                                            AS product_name,
  game.couverture                                                    AS main_image,
  concat('Critique de ', productTranslation.name)                    AS title,
  concat('critique-', productTranslation.slug, '-ra-', test.game_id) AS name,
  topic.id                                                           AS topic_id,
  customer.id                                                        AS author_id,
  test.intro                                                         AS introduction,
  test.materiel                                                      AS material,
  test.regle                                                         AS rules,
  test.duree_vie                                                     AS lifetime,
  test.conseil                                                       AS advice,
  test.note_materiel / 2                                             AS materialRating,
  test.note_regle / 2                                                AS rulesRating,
  test.note_duree_vie / 2                                            AS lifetimeRating,
  test.valid                                                         AS publishable,
  review_article_view.view_count,
  material_image.img_nom                                             AS material_image_path,
  rules_image.img_nom                                                AS rules_image_path,
  lifetime_image.img_nom                                             AS lifetime_image_path
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
EOM;

        if ($this->input->getOption('no-update')) {
            $query .= <<<EOM

WHERE not exists (
   select 0
   from jdj_article a
   where a.code = concat('review-article-', test.game_id)
)
EOM;
        }

        $query .= <<<EOM

order by    test.date desc
EOM;

        if ($this->input->hasOption('limit')) {
            $query .= sprintf(' limit %s', $this->input->getOption('limit'));
        }

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @return array
     */
    protected function getTotalOfItemsLoaded()
    {
        $query = <<<EOM
select count(article.id) as itemCount, count(0) as totalCount
from jedisjeux.jdj_tests old
  left join jdj_article article
    on article.code = concat('review-article-', old.game_id);
EOM;

        return $this->getDatabaseConnection()->fetchAssoc($query);
    }
}
