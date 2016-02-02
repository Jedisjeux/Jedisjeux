<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 31/01/16
 * Time: 18:58
 */

namespace AppBundle\Command;

use AppBundle\Document\ArticleBlock;
use AppBundle\Document\ArticleContent;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\Document\Resource;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentRepository;
use PHPCR\Util\NodeHelper;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\File;
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
            $blocks = $this->getBlocks($data['blocks']);
            $this->populateBlocks($page, $blocks);
            $this->getManager()->persist($page);
            $this->getManager()->flush();
            $this->getManager()->clear();
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
        $article->setName($data['name']);
        $article->setTitle($data['title']);
        $article->setPublishable(true);

        return $article;
    }

    protected function populateBlocks(ArticleContent $page, array $blocks)
    {
        foreach ($blocks as $data) {
            $block = $this->createOrReplaceBlock($page, $data);
            $page->addChildren($block);
            if (isset($data['image'])) {
                $this->createOrReplaceImagineBlock($block, $data);
            }

        }
    }

    /**
     * @param ArticleContent $page
     * @param array $data
     * @return ArticleBlock
     */
    protected function createOrReplaceBlock(ArticleContent $page, array $data)
    {
        $name = 'block'.$data['id'];

        $block = $this
            ->getArticleBlockRepository()
            ->findOneBy(array('name' => $name));

        if (null === $block) {
            $block = new ArticleBlock();
            $block
                ->setParentDocument($page);
        }

        $block
            ->setImagePosition($data['image_position'])
            ->setTitle($data['title'])
            ->setBody(sprintf('<p>%s</p>', $data['body']))
            ->setName($name)
            ->setPublishable(true);

        return $block;
    }

    protected function createOrReplaceImagineBlock(ArticleBlock $block, array $data)
    {
        $name = 'image'.$data['id'];

        if (false === $block->hasChildren()) {
            $imagineBlock = new ImagineBlock();
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
            ->setImage($image);

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
    public function getArticleBlockRepository()
    {
        return $this->getContainer()->get('app.repository.article_block');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('app.manager.article_content');
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
select replace(titre_clean, ' ', '-') as name,
      titre as title,
      group_concat(block.text_id) as blocks
from jedisjeux.jdj_article article
inner join jedisjeux.jdj_article_text as block
      on block.article_id = article.article_id
where titre_clean != ''
group by article.article_id
limit 1
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
                    when 1 then 'left'
                    when 2 then 'right'
                    when 5 then 'top'
                    when 6 then 'top'
                end as image_position,
                image.img_nom as image
        from jedisjeux.jdj_article_text as block
        left join jedisjeux.jdj_images image
                    on image.img_id = block.img_id
        where block.text_id in ($ids)
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
}