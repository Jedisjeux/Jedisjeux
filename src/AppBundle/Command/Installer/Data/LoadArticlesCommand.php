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
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Exception\InvalidArgumentException;
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
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Set limit of articles to import')
            ->addOption('main-taxon', null, InputOption::VALUE_REQUIRED, 'Restrict articles with given taxons');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_collect_cycles();

        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getArticles() as $key => $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> article", $data['title']));
            $this->logMemoryUsage($output);

            $article = $this->createOrReplaceArticle($data);
            $articleDocument = $article->getDocument();

            $block = $this->createOrReplaceIntroductionBlock($articleDocument, $data);
            $articleDocument->addChild($block);
            $blocks = $this->getBlocks($data['blocks']);

            $this->getDocumentManager()->persist($articleDocument);
            $this->getDocumentManager()->flush();

            if (Taxon::CODE_REPORT_ARTICLE === $data['mainTaxon']) {
                $slideshowBlock = $this->createOrReplaceSlideshowBlock($articleDocument);
                $this->getDocumentManager()->persist($slideshowBlock);
                $this->getDocumentManager()->flush();
                $this->populateBlocks($slideshowBlock, $blocks);
            } else {
                $this->populateBlocks($articleDocument, $blocks);
            }

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
            ->setCode(sprintf('article-%s', $data['id']))
            ->setViewCount($data['view_count'])
            ->setStatus($data['publishable'] ? Article::STATUS_PUBLISHED : Article::STATUS_NEW);

        $articleDocument = $article->getDocument();

        if (null === $articleDocument) {
            $articleDocument = $this->getDocumentFactory()->createNew();
            $article
                ->setDocument($articleDocument);
        }

        if (null !== $data['mainImage']) {
            $mainImage = $this->getContainer()->get('app.factory.imagine_block')->createNew();

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
        $articleDocument->setPublishable($data['publishable']);
        $articleDocument->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']));

        return $article;
    }

    /**
     * @param ArticleContent $page
     * @param array $data
     *
     * @return BlockquoteBlock
     */
    protected function createOrReplaceIntroductionBlock(ArticleContent $page, array $data)
    {
        /** @var BlockquoteBlock $block */
        $block = $page->getChildren()->first();

        if (false === $block) {
            $block = new BlockquoteBlock();
            $block
                ->setParentDocument($page);
        }

        $block
            ->setBody(sprintf('<p>%s</p>', $data['introduction']))
            ->setName('introduction')
            ->setPublishable(true);

        return $block;
    }

    /**
     * @param ArticleContent $page
     *
     * @return SlideshowBlock
     */
    protected function createOrReplaceSlideshowBlock(ArticleContent $page)
    {
        /** @var SlideshowBlock $block */
        $block = $page->getChildren()->next();

        if (false === $block) {
            $block = new SlideshowBlock();
            $block
                ->setParentDocument($page);
        }

        $block
            ->setTitle('Slideshow')
            ->setName('slideshow')
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
            when 'reportage' then 'report-articles'
            when 'interview' then 'interviews'
            when 'cdlb' then 'in-the-boxes'
            when 'preview' then 'previews'
            else null
       end as mainTaxon,     
       article.valid as publishable,
       product.id as product_id,
       topic.id as topic_id,
       user.customer_id as author_id,
       group_concat(block.text_id ORDER BY block.ordre) as blocks,
       article_view.view_count
from jedisjeux.jdj_article article
  inner join jedisjeux.jdj_article_text as block
    on block.article_id = article.article_id
  inner join jedisjeux.jdj_v_article_view_count as article_view  
    on article_view.id = article.article_id
  left join sylius_product_variant productVariant
    on productVariant.code = concat('game-', article.game_id)
  left join sylius_product product
    on product.id = productVariant.product_id
  left join jdj_topic topic
    on topic.id = article.topic_id
  left join sylius_user user
    on convert(user.username USING UTF8) = convert(article.auteur USING UTF8)
where titre_clean != ''
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

        if ($this->input->getOption('main-taxon')) {

            switch ($this->input->getOption('main-taxon')) {
                case Taxon::CODE_REPORT_ARTICLE:
                    $type = 'reportage';
                    break;
                case Taxon::CODE_PREVIEWS:
                    $type = 'preview';
                    break;
                case Taxon::CODE_IN_THE_BOXES:
                    $type = 'cdlb';
                    break;
                case Taxon::CODE_INTERVIEW:
                    $type = 'interview';
                    break;
                default:
                    $type = null;
            }

            if (null === $type) {
                Throw new InvalidArgumentException(sprintf('Type %s not found', $this->input->getOption('main-taxon')));
            }

            $query .= sprintf(" AND article.type_article = '%s'", $type);
        }

        $query .= <<<EOM
        
        group by article.article_id
EOM;

        $query .= <<<EOM
 
order by    article.date desc
EOM;

        if ($this->input->getOption('limit')) {
            $query .= sprintf(' limit %s', $this->input->getOption('limit'));
        }

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @param string $ids
     *
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

    /**
     * @return array
     */
    protected function getTotalOfItemsLoaded()
    {
        $query = <<<EOM
select count(article.id) as itemCount, count(0) as totalCount
from jedisjeux.jdj_article old
  left join jdj_article article
    on article.code = concat('article-', old.article_id)
EOM;

        return $this->getDatabaseConnection()->fetchAssoc($query);
    }
}