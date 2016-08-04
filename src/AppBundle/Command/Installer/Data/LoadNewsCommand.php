<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/04/2016
 * Time: 09:04
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Command\LogMemoryUsageTrait;
use AppBundle\Document\ArticleContent;
use AppBundle\Document\SingleImageBlock;
use AppBundle\Entity\Article;
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use AppBundle\TextFilter\Bbcode2Html;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Doctrine\ORM\EntityManager;
use PHPCR\Util\NodeHelper;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadNewsCommand extends AbstractLoadDocumentCommand
{
    use LogMemoryUsageTrait;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:news:load')
            ->setDescription('Load news');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_collect_cycles();
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getNews() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> news", $data['title']));
            $this->logMemoryUsage($output);

            $page = $this->createOrReplaceArticle($data);
            $blocks = [
                [
                    'id' => $data['id'],
                    'body' => $data['body'],
                    'image_position' => 'left',
                    'image_label' => null,
                    'image' => $data['mainImage'],
                    'title' => null,
                    'class' => null,
                ]
            ];
            $this->populateBlocks($page, $blocks);

            $this->getManager()->persist($page);
            $this->getManager()->flush();

            /** @var Article $article */
            $article = $this->getContainer()->get('app.repository.article')->findOneBy(['documentId' => $page->getId()]);

            if (null === $article) {
                /** @var Article $article */
                $article = $this->getContainer()->get('app.factory.article')->createNew();
                $article
                    ->setDocument($page);
            }

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = $this->getTaxonRepository()->findOneBy(['code' => Taxon::CODE_NEWS]);
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

            $this->getArticleManager()->persist($article);
            $this->getArticleManager()->flush();
            $this->getManager()->clear();
            $this->getArticleManager()->clear();


        }
    }

    protected function getNews()
    {
        $query = <<<EOM
select      old.news_id as id,
            old.titre as title,
            replace(old.titre_clean, ' ', '-') as name,
            old.date as publishedAt,
            old.text as body,
            old.photo as mainImage,
            customer.id as author_id,
            old.photo as mainImage,
            product.id as product_id,
            topic.id as topic_id
from        jedisjeux.jdj_news old
  inner join sylius_customer customer
    on customer.code = concat('user-', old.user_id)
  left join sylius_product_variant productVariant
    on productVariant.code = concat('game-', old.game_id)
  left join sylius_product product
    on product.id = productVariant.product_id
  left join jdj_topic topic
    on topic.id = old.topic_id
WHERE       old.valid = 1
            AND       old.type_lien in (0, 1)
order by    old.date desc
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    protected function populateBlocks(ArticleContent $page, array $blocks)
    {
        foreach ($blocks as $data) {
            $block = $this->createOrReplaceBlock($page, $data);
            $page->addChild($block);
            if (isset($data['image'])) {
                $this->createOrReplaceImagineBlock($block, $data);
            }
        }
    }

    /**
     * @param array $data
     * @return ArticleContent
     */
    protected function createOrReplaceArticle(array $data)
    {
        $article = $this->findPage($data['name']);

        if (null === $article) {
            $article = new ArticleContent();
            $article
                ->setParentDocument($this->getParent());

        }

        if (null !== $data['mainImage']) {
            $mainImage = $article->getMainImage();

            if (null === $mainImage) {
                $mainImage = new ImagineBlock();
            }


            $image = new Image();
            $image->setFileContent(file_get_contents($this->getImageOriginalPath($data['mainImage'])));

            $mainImage
                ->setParentDocument($article)
                ->setImage($image);

            // $this->getManager()->persist($mainImage);

            $article
                ->setMainImage($mainImage);
        }

        $article->setName($data['name']);
        $article->setTitle($data['title']);
        $article->setPublishable(true);
        $article->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']));

        return $article;
    }

    /**
     * @param ArticleContent $page
     * @param array $data
     * @return SingleImageBlock
     */
    protected function createOrReplaceBlock(ArticleContent $page, array $data)
    {
        $name = 'block'.$data['id'];

        $block = $this
            ->getSingleImageBlockRepository()
            ->findOneBy(array('name' => $name));

        if (null === $block) {
            $block = new SingleImageBlock();
            $block
                ->setParentDocument($page);
        }
        
        $bbcode2html = new Bbcode2Html();
        $body = $data['body'];
        $body = $bbcode2html
            ->setBody($body)
            ->getFilteredBody();
        

        $block
            ->setImagePosition($data['image_position'])
            ->setTitle($data['title'])
            ->setBody($body)
            ->setName($name)
            ->setClass($data['class'] ?: null)
            ->setPublishable(true);

        return $block;
    }

    protected function createOrReplaceImagineBlock(SingleImageBlock $block, array $data)
    {
        $name = 'image'.$data['id'];

        if (false === $block->hasChildren()) {
            $imagineBlock = new ImagineBlock();
            $block
                ->addChild($imagineBlock);
        } else {
            /** @var ImagineBlock $imagineBlock */
            $imagineBlock = $block->getChildren()->first();
        }

        $image = new Image();
        $image->setFileContent(file_get_contents($this->getImageOriginalPath($data['image'])));
        $image->setName($data['image']);

        $imagineBlock
            ->setName($name)
            ->setParentDocument($block)
            ->setImage($image)
            ->setLabel($data['image_label']);

        $this->getManager()->persist($imagineBlock);

        return $imagineBlock;
    }

    /**
     * @param string $name
     * @return ArticleContent
     */
    protected function findPage($name)
    {
        $page = $this
            ->getRepository()
            ->findOneBy(array('name' => $name));

        return $page;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getImageOriginalPath($path)
    {
        return "http://www.jedisjeux.net/img/800/".$path;
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.article_content');
    }

    /**
     * @return DocumentRepository
     */
    public function getSingleImageBlockRepository()
    {
        return $this->getContainer()->get('app.repository.single_image_block');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('app.manager.article_content');
    }

    /**
     * @return EntityManager
     */
    protected function getArticleManager()
    {
        return $this->getContainer()->get('app.manager.article');
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
