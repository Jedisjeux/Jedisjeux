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

use AppBundle\Document\ArticleContent;
use AppBundle\Document\SingleImageBlock;
use AppBundle\TextFilter\Bbcode2Html;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
abstract class AbstractLoadDocumentCommand extends ContainerAwareCommand
{
    /**
     * @param ArticleContent $page
     * @param array $blocks
     */
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
     *
     * @return SingleImageBlock
     */
    protected function createOrReplaceBlock(ArticleContent $page, array $data)
    {
        $name = 'block' . $data['id'];

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
    
    /**
     * @param SingleImageBlock $block
     * @param array $data
     *
     * @return ImagineBlock
     */
    protected function createOrReplaceImagineBlock(SingleImageBlock $block, array $data)
    {
        $name = 'image' . $data['id'];

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

        $this->getDocumentManager()->persist($imagineBlock);

        return $imagineBlock;
    }
    
    /**
     * @param string $path
     *
     * @return string
     */
    protected function getImageOriginalPath($path)
    {
        return "http://www.jedisjeux.net/img/800/" . $path;
    }

    /**
     * @return DocumentRepository
     */
    protected function getSingleImageBlockRepository()
    {
        return $this->getContainer()->get('app.repository.single_image_block');
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
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
}
