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
use Sylius\Bundle\InstallerBundle\Command\CommandExecutor;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\AbstractBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
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
     * @param ContainerBlock $parent
     * @param array $blocks
     */
    protected function populateBlocks(ContainerBlock $parent, array $blocks)
    {
        foreach ($blocks as $data) {
            $block = $this->createOrReplaceBlock($parent, $data);
            $parent->addChild($block);
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

        $id = $parent->getParentDocument()->getId().'/'.$name;

        try {
            $block = $this
                ->getSingleImageBlockRepository()
                ->findOneBy(array('id' => $id));
        } catch(PathNotFoundException $exception) {
            $block = new SingleImageBlock();
            $block
                ->setParentDocument($parent);
        }

        if (null === $block) {
            $block = new SingleImageBlock();
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
     * @return Bbcode2Html
     */
    protected function getBbcode2Html()
    {
        return $this->getContainer()->get('app.text.filter.bbcode2html');
    }

    /**
     * @return DocumentRepository
     */
    protected function getSingleImageBlockRepository()
    {
        return $this->getContainer()->get('app.repository.single_image_block');
    }

    /**
     * @return Factory
     */
    public function getDocumentFactory()
    {
        return $this->getContainer()->get('app.factory.article_content');
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->getContainer()->get('app.factory.article');
    }

    /**
     * @return TaxonRepository
     */
    public function getTaxonRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.article');
    }

    /**
     * @return DocumentRepository
     */
    public function getDocumentRepository()
    {
        return $this->getContainer()->get('app.repository.article_content');
    }

    /**
     * @return EntityManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('app.manager.article');
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
