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
use AppBundle\Entity\Article;
use AppBundle\Entity\Topic;
use Doctrine\ODM\PHPCR\ChildrenCollection;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Doctrine\ORM\EntityManager;
use PHPCR\Util\NodeHelper;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTestsCommand extends AbstractLoadDocumentCommand
{
    use LogMemoryUsageTrait;

    /**
     * @var Generic
     */
    protected $parent;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:tests:load')
            ->setDescription('Load tests');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_collect_cycles();
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $this->parent = $this->getParent();

        foreach ($this->getTests() as $data) {
            $output->writeln(sprintf("Loading test of <info>%s</info> product", $data['product_name']));

            $this->logMemoryUsage($output);

            $page = $this->createOrReplaceTest($data);
            $block = $this->createOrReplaceIntroductionBlock($page, $data);
            $page->addChild($block);
            $blocks = [
                [
                    'id' => $data['name'] . '0',
                    'body' => $data['material'],
                    'image_position' => 'left',
                    'image_label' => null,
                    'image' => $data['material_image_path'],
                    'title' => 'Matériel',
                    'class' => null,
                ], [
                    'id' => $data['name'] . '1',
                    'body' => $data['rules'],
                    'image_position' => 'right',
                    'image_label' => null,
                    'image' => $data['rules_image_path'],
                    'title' => 'Règles',
                    'class' => null,
                ], [
                    'id' => $data['name'] . '2',
                    'body' => $data['lifetime'],
                    'image_position' => 'center',
                    'image_label' => null,
                    'image' => $data['lifetime_image_path'],
                    'title' => 'Durée de vie',
                    'class' => null,
                ]
            ];
            $this->populateBlocks($page, $blocks);

            $this->getDocumentManager()->persist($page);
            $this->getDocumentManager()->flush();

            $article = $this->getContainer()->get('app.repository.article')->findOneBy(['documentId' => $page->getId()]);

            if (null === $article) {
                /** @var Article $article */
                $article = $this->getContainer()->get('app.factory.article')->createNew();
                $article
                    ->setDocument($page);
            }

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

            $this->getManager()->persist($article);
            $this->getManager()->flush();
            $this->getManager()->clear();
            $this->getDocumentManager()->flush();
            $this->getDocumentManager()->clear();
        }
    }

    /**
     * @param array $data
     * @return ArticleContent
     */
    protected function createOrReplaceTest(array $data)
    {
        $articleDocument = $this->findPage($data['name']);

        if (null === $articleDocument) {
            $articleDocument = new ArticleContent();
            $articleDocument
                ->setParentDocument($this->parent);
        }

        if (null !== $data['main_image']) {
            $mainImage = $articleDocument->getMainImage();

            if (null === $mainImage) {
                $mainImage = new ImagineBlock();
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
        $articleDocument->setPublishable((bool)$data['published']);
        $articleDocument->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']));

        return $articleDocument;
    }

    protected function createOrReplaceIntroductionBlock(ArticleContent $page, array $data)
    {
        $name = 'block0';

        $block = $this
            ->getSingleImageBlockRepository()
            ->findOneBy(array('name' => $name));

        if (null === $block) {
            $block = new BlockquoteBlock();
            $block
                ->setParentDocument($page);
        }

        $block
            ->setBody(sprintf('<p>%s</p>', $data['introduction']))
            ->setName($name)
            ->setPublishable(true);

        return $block;
    }

    /**
     * @param string $name
     * @return ArticleContent
     */
    protected function findPage($name)
    {
        $id = $this->parent->getId() . '/' .  $name;

        /** @var ArticleContent $page */
        $page = $this
            ->getRepository()
            ->find($id);

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        $contentBasepath = '/cms/pages/articles/tests';
        /** @var Generic $parent */
        $parent = $this->getDocumentManager()->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->getDocumentManager()->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->getDocumentManager()->find(null, $contentBasepath);
        }

        return $parent;
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.article_content');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('app.manager.article');
    }

    /**
     * @return array
     */
    protected function getTests()
    {
        $query = <<<EOM
select test.game_id as id,
       test.date as publishedAt,
       product.id as product_id,
       productTranslation.name as product_name,
       game.couverture as main_image,
       concat('Test de ', productTranslation.name) as title,
       concat('test-de-', productTranslation.slug) as name,
       topic.id as topic_id,
       customer.id as author_id,
       test.intro as introduction,
       test.materiel as material,
       test.regle as rules,
       test.duree_vie as lifetime,
       test.note_materiel/2 as materialRating,
       test.note_regle/2 as rulesRating,
       test.note_duree_vie/2 as lifetimeRating,
       test.valid as published,
       material_image.img_nom as material_image_path,
       rules_image.img_nom as rules_image_path,
       lifetime_image.img_nom as lifetime_image_path
from jedisjeux.jdj_tests test
  inner join jedisjeux.jdj_game game
    on game.id = test.game_id
  inner join sylius_product_variant productVariant
    on productVariant.code = concat('game-', test.game_id)
  inner join sylius_product product
    on product.id = productVariant.product_id
  inner join sylius_product_translation productTranslation
    on productTranslation.translatable_id = product.id
       and locale = 'fr'
  left join jdj_topic topic
    on topic.id = test.topic_id
  inner join sylius_customer customer
    on customer.code = concat('user-', test.user_id)
  left join jedisjeux.jdj_images_elements material_img
        on material_img.elem_type = 'test'  
        and material_img.elem_id = test.game_id
        and material_img.ordre = 1
  left join jedisjeux.jdj_images material_image
        on material_image.img_id = material_img.img_id
  left join jedisjeux.jdj_images_elements rules_img
        on rules_img.elem_type = 'test'  
        and rules_img.elem_id = test.game_id
        and rules_img.ordre = 2
  left join jedisjeux.jdj_images rules_image
        on rules_image.img_id = rules_img.img_id
  left join jedisjeux.jdj_images_elements lifetime_img
        on lifetime_img.elem_type = 'test'  
        and lifetime_img.elem_id = test.game_id
        and lifetime_img.ordre = 3
  left join jedisjeux.jdj_images lifetime_image
        on lifetime_image.img_id = lifetime_img.img_id
  order by test.date desc
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }
}
