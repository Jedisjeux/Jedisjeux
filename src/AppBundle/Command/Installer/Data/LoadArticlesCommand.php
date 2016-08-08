<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 31/01/16
 * Time: 18:58
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Command\LogMemoryUsageTrait;
use AppBundle\Document\BlockquoteBlock;
use AppBundle\Document\ArticleContent;
use AppBundle\Entity\Article;
use AppBundle\Entity\Topic;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadArticlesCommand extends AbstractLoadDocumentCommand
{
    use LogMemoryUsageTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:articles:load')
            ->setDescription('Load articles')
            ->addOption('no-update')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Set limit of articles to import');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_collect_cycles();

        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getArticles() as $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> article", $data['title']));
            $this->logMemoryUsage($output);

            $article = $this->createOrReplaceArticle($data);
            $articleDocument = $article->getDocument();

            $block = $this->createOrReplaceIntroductionBlock($articleDocument, $data);
            $articleDocument->addChild($block);
            $blocks = $this->getBlocks($data['blocks']);
            $this->populateBlocks($articleDocument, $blocks);

            $this->getDocumentManager()->persist($articleDocument);
            $this->getDocumentManager()->flush();

            if (null !== $data['mainTaxon']) {
                /** @var TaxonInterface $mainTaxon */
                $mainTaxon = $this->getTaxonRepository()->findOneBy(['code' => $data['mainTaxon']]);
                $article->setMainTaxon($mainTaxon);
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

            $this->getDocumentManager()->persist($articleDocument);
            $this->getDocumentManager()->flush();

            $this->getManager()->persist($article);
            $this->getManager()->flush();
            $this->getManager()->clear();

            $this->getDocumentManager()->detach($articleDocument);
            $this->getDocumentManager()->clear();
        }

        $this->clearDoctrineCache();
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
            ->setCode(sprintf('article-%s', $data['id']));

        $articleDocument = $article->getDocument();

        if (null === $articleDocument) {
            $articleDocument = $this->getDocumentFactory()->createNew();
            $article
                ->setDocument($articleDocument);
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

        return $article;
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
     * @return array
     */
    protected function getArticles()
    {
        $query = <<<EOM
select article.article_id as id,
       concat(replace(article.titre_clean, ' ', '-'), '-a-', article.article_id) as name,
       article.titre as title,
       article.date as publishedAt,
       article.intro as introduction,
       article.photo as mainImage,
       case article.type_article
            when 'article' then null
            when 'interview' then 'interviews'
            when 'cdlb' then 'in-the-boxes'
            when 'preview' then 'previews'
            else null
       end as mainTaxon,     
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
EOM;

        if ($this->input->hasOption('no-update')) {
            $query .= <<<EOM

AND not exists (
   select 0
   from jdj_article a
   where a.code = concat('article-', article.article_id)
)
EOM;
        }

        if ($this->input->hasOption('limit')) {
            $query .= sprintf(' limit %s', $this->input->getOption('limit'));
        }

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