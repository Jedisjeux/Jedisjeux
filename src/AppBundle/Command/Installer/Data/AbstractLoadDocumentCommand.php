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
use AppBundle\Document\ImagineBlock;
use AppBundle\Document\SingleImageBlock;
use AppBundle\Entity\Article;
use AppBundle\Repository\TaxonRepository;
use AppBundle\TextFilter\Bbcode2Html;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Jackalope\NodeType\NodeProcessor;
use PHPCR\PathNotFoundException;
use PHPCR\Util\NodeHelper;
use AppBundle\Command\Installer\CommandExecutor;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
abstract class AbstractLoadDocumentCommand extends ContainerAwareCommand
{
    /**
     * @var Generic
     */
    protected $parent;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var CommandExecutor
     */
    protected $commandExecutor;

    /**
     * @var bool
     */
    private $isErrored = false;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $application = $this->getApplication();
        $application->setCatchExceptions(false);

        $this->commandExecutor = new CommandExecutor(
            $input,
            $output,
            $application
        );
    }

    /**
     * @param ArticleContent $parent
     * @param array $blocks
     */
    protected function populateBlocks(ArticleContent $parent, array $blocks, SlideshowBlock $slideshowBlock = null)
    {
        foreach ($blocks as $data) {
            if (null !== $slideshowBlock && isset($data['image'])) {
                $block = $this->createOrReplaceBlock($slideshowBlock, $data);
                $this->createOrReplaceImagineBlock($block, $data);
                $slideshowBlock->addChild($block);
                continue;
            }

            $block = $this->createOrReplaceBlock($parent, $data);
            $parent->addBlock($block);

            if (isset($data['image'])) {
                $this->createOrReplaceImagineBlock($block, $data);
            }
        }
    }

    /**
     * @param ContainerBlock $parent
     * @param array $data
     *
     * @return SingleImageBlock
     */
    protected function createOrReplaceBlock(ContainerBlock $parent, array $data)
    {
        $name = 'block' . $data['id'];

        $id = $parent->getId().'/'.$name;

        switch ($data['image_position']) {
            case SingleImageBlock::POSITION_LEFT:
                $alias = 'left_image_block';
                break;
            case SingleImageBlock::POSITION_RIGHT:
                $alias = 'right_image_block';
                break;
            case SingleImageBlock::POSITION_TOP:
                $alias = 'top_image_block';
                break;
            default:
                $alias = 'single_image_block';
                break;
        }

        /** @var FactoryInterface $factory */
        $factory = $this->getContainer()->get('app.factory.' . $alias);
        /** @var RepositoryInterface $repository */
        $repository = $this->getContainer()->get('app.repository.' . $alias);

        try {
            /** @var SingleImageBlock $block */
            $block = $repository
                ->find($id);
        } catch(PathNotFoundException $exception) {
            $block = $factory->createNew();
            $block
                ->setParentDocument($parent);
        }

        if (null === $block) {
            $block = $factory->createNew();
            $block
                ->setParentDocument($parent);
        }

        $bbcode2html = $this->getBbcode2Html();
        $body = $data['body'];
        $body = $bbcode2html
            ->setBody($body)
            ->getFilteredBody();

        $body = preg_replace(NodeProcessor::VALIDATE_STRING, '', $body);

        $block
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
        if (null === $data['image'] || empty($data['image'])) {
            return null;
        }

        $originalPath = $this->getImageOriginalPath($data['image']);

        if (null === $imagineBlock = $block->getImagineBlock()) {
            /** @var ImagineBlock $imagineBlock */
            $imagineBlock = $this->getContainer()->get('app.factory.imagine_block')->createNew();
            $block
                ->setImagineBlock($imagineBlock);
        }

        $image = new Image();
        $image->setFileContent(file_get_contents($originalPath));
        $image->setName($data['image']);

        $imagineBlock
            ->setImage($image)
            ->setLabel($data['image_label']);

        $this->getDocumentManager()->persist($imagineBlock);

        return $imagineBlock;
    }

    /**
     * @param string $name
     *
     * @return Article
     */
    protected function findArticle($name)
    {
        $documentId = $this->getParent()->getId() . '/' . $name;

        /** @var Article $article */
        $article = $this
            ->getRepository()
            ->findOneBy(['documentId' => $documentId]);

        return $article;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        if (null !== $this->parent) {
            return $this->parent;
        }

        $contentBasepath = '/cms/pages/articles';
        /** @var Generic $parent */
        $parent = $this->getDocumentManager()->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->getDocumentManager()->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->getDocumentManager()->find(null, $contentBasepath);
        }

        $this->parent = $parent;

        return $this->parent;
    }

    /**
     * @param integer $itemCount
     * @param integer $totalCount
     */
    protected function showTotalOfItemsLoaded($itemCount, $totalCount)
    {
        $percentage = round($itemCount * 100 / $totalCount);

        $this->output->writeln(sprintf('<comment>%s</comment> items loaded of <comment>%s</comment> (<comment>%s percent</comment>)', $itemCount, $totalCount, $percentage));
    }

    protected function clearDoctrineCache()
    {
        $commands = [
            'clear-metadata',
            'clear-query',
            'clear-result',
        ];

        foreach ($commands as $step => $command) {
            try {
                $this->commandExecutor->runCommand('doctrine:cache:'.$command, [], $this->output);
                $this->output->writeln('');
            } catch (RuntimeException $exception) {
                $this->isErrored = true;

                continue;
            }
        }
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
     * @return Bbcode2Html|object
     */
    protected function getBbcode2Html()
    {
        return $this->getContainer()->get('app.text.filter.bbcode2html');
    }

    /**
     * @return DocumentRepository|object
     */
    protected function getSingleImageBlockRepository()
    {
        return $this->getContainer()->get('app.repository.single_image_block');
    }

    /**
     * @return Factory|object
     */
    public function getDocumentFactory()
    {
        return $this->getContainer()->get('app.factory.article_content');
    }

    /**
     * @return Factory|object
     */
    public function getFactory()
    {
        return $this->getContainer()->get('app.factory.article');
    }

    /**
     * @return TaxonRepository|object
     */
    public function getTaxonRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityRepository|object
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.article');
    }

    /**
     * @return DocumentRepository|object
     */
    public function getDocumentRepository()
    {
        return $this->getContainer()->get('app.repository.article_content');
    }

    /**
     * @return EntityManager|object
     */
    public function getManager()
    {
        return $this->getContainer()->get('app.manager.article');
    }

    /**
     * @return DocumentManager|object
     */
    public function getDocumentManager()
    {
        return $this->getContainer()->get('app.manager.article_content');
    }

    /**
     * @return \Doctrine\DBAL\Connection|object
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }
}
