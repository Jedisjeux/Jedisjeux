<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 31/01/16
 * Time: 18:58
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Document\BlockquoteBlock;
use AppBundle\Document\SingleImageBlock;
use AppBundle\Document\ArticleContent;
use AppBundle\Entity\Article;
use AppBundle\Entity\Topic;
use AppBundle\TextFilter\Bbcode2Html;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Doctrine\ORM\EntityManager;
use PHPCR\Util\NodeHelper;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadArticlesCommand extends AbstractLoadDocumentCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:articles:load')
            ->setDescription('Load articles');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getArticles() as $data) {
            $page = $this->createOrReplaceArticle($data);
            $block = $this->createOrReplaceIntroductionBlock($page, $data);
            $page->addChild($block);
            $blocks = $this->getBlocks($data['blocks']);
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

            $this->getManager()->persist($article);
            $this->getManager()->flush();
            $this->getManager()->detach($article);
            $this->getManager()->clear();
        }
    }

    /**
     * @param array $data
     * @return ArticleContent
     */
    protected function createOrReplaceArticle(array $data)
    {
        $articleDocument = $this->findPage($data['name']);

        if (null === $articleDocument) {
            $articleDocument = new ArticleContent();
            $articleDocument
                ->setParentDocument($this->getParent());
        }

        if (null !== $data['mainImage']) {
            $mainImage = $articleDocument->getMainImage();

            if (null === $mainImage) {
                $mainImage = new ImagineBlock();
            }


            $image = new Image();
            $image->setFileContent(file_get_contents($this->getImageOriginalPath($data['mainImage'])));

            $mainImage
                ->setParentDocument($articleDocument)
                ->setImage($image);

            $articleDocument
                ->setMainImage($mainImage);
        }

        $articleDocument->setName($data['name']);
        $articleDocument->setTitle($data['title']);
        $articleDocument->setPublishable(true);
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
     *
     * @return ArticleContent
     */
    protected function findPage($name)
    {
        /** @var ArticleContent $page */
        $page = $this
            ->getRepository()
            ->findOneBy(array('name' => $name));

        return $page;
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.article_content');
    }

    /**
     * @return array
     */
    protected function getArticles()
    {
        $query = <<<EOM
select article.article_id as id,
       replace(article.titre_clean, ' ', '-') as name,
       article.titre as title,
       article.date as publishedAt,
       article.intro as introduction,
       article.photo as mainImage,
       product.id as product_id,
       topic.id as topic_id,
       user.customer_id as author_id,
       group_concat(block.text_id ORDER BY block.ordre) as blocks
from jedisjeux.jdj_article article
  inner join jedisjeux.jdj_article_text as block
    on block.article_id = article.article_id
  left join sylius_product_variant productVariant
    on productVariant.code = concat('game-', article.game_id)
  left join sylius_product product
    on product.id = productVariant.product_id
  left join jdj_topic topic
    on topic.id = article.topic_id
  left join sylius_user user
    on convert(user.username USING UTF8) = convert(article.auteur USING UTF8)
where titre_clean != ''
group by article.article_id
limit 5
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @param string $ids
     * @return array
     */
    protected function getBlocks($ids)
    {
        $query = <<<EOM
        select block.text_id as id,
                block.text_titre as title,
                block.text as body,
                case block.style
                    when 5 then 'well'
                end as class,
                case block.style
                    when 1 then 'left'
                    when 2 then 'right'
                    when 5 then 'top'
                    when 6 then 'top'
                end as image_position,
                block.ordre as position,
                image.img_nom as image,
                imageElements.legende as image_label
        from jedisjeux.jdj_article_text as block
        left join jedisjeux.jdj_images image
                    on image.img_id = block.img_id
        left join jedisjeux.jdj_images_elements imageElements
                on imageElements.img_id = image.img_id
                and imageElements.elem_type = 'article'
                and imageElements.elem_id = block.article_id
        where block.text_id in ($ids)
        order by block.ordre
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }


}