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
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Doctrine\ORM\EntityManager;
use PHPCR\Util\NodeHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadArticlesCommand extends ContainerAwareCommand
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
            $this->getManager()->persist($page);
            $this->getManager()->flush();
            $this->getManager()->clear();

            $article = $this->getContainer()->get('app.repository.article')->findOneBy(['documentId' => $page->getId()]);

            if (null === $article) {
                /** @var Article $article */
                $article = $this->getContainer()->get('app.factory.article')->createNew();
                $article
                    ->setDocument($page);
            }

            $this->getArticleManager()->persist($article);
            $this->getArticleManager()->flush();
            $this->getArticleManager()->clear();
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

            // $this->getManager()->persist($mainImage);

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

        $block
            ->setImagePosition($data['image_position'])
            ->setTitle($data['title'])
            ->setBody($this->nl2p($data['body']))
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
     * @return Generic
     */
    protected function getParent()
    {
        $contentBasepath = '/cms/pages/articles';
        $parent = $this->getManager()->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->getManager()->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->getManager()->find(null, $contentBasepath);
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

    protected function getArticles()
    {
        $query = <<<EOM
select article.article_id as id,
      replace(article.titre_clean, ' ', '-') as name,
      article.titre as title,
      article.date as publishedAt,
      article.intro as introduction,
      article.photo as mainImage,
      group_concat(block.text_id) as blocks
from jedisjeux.jdj_article article
inner join jedisjeux.jdj_article_text as block
      on block.article_id = article.article_id
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

    /**
     * @param string $path
     * @return string
     */
    protected function getImageOriginalPath($path)
    {
        return "http://www.jedisjeux.net/img/800/".$path;
    }

    protected function nl2p($string)
    {
        return "<p>".str_replace("\n", "</p><p>", trim($string))."</p>";
    }
}