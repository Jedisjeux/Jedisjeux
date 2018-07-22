<?php

declare(strict_types=1);

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Document;

use AppBundle\Entity\Topic;
use AppBundle\Factory\Document\ArticleDocumentFactory;
use AppBundle\Factory\Document\ProductDocumentFactory;
use AppBundle\Factory\Document\TopicDocumentFactory;
use ONGR\ElasticsearchBundle\Service\Manager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

final class ResetIndexCommand extends Command
{
    /**
     * @var RepositoryInterface
     */
    private $articleRepository;

    /**
     * @var RepositoryInterface
     */
    private $productRepository;

    /**
     * @var RepositoryInterface
     */
    private $topicRepository;

    /**
     * @var Manager
     */
    private $elasticsearchManager;

    /**
     * @var ArticleDocumentFactory
     */
    private $articleDocumentFactory;

    /**
     * @var ProductDocumentFactory
     */
    private $productDocumentFactory;

    /**
     * @var TopicDocumentFactory
     */
    private $topicDocumentFactory;

    /**
     * @param RepositoryInterface $articleRepository
     * @param RepositoryInterface $productRepository
     * @param RepositoryInterface $topicRepository
     * @param Manager $manager
     * @param ArticleDocumentFactory $articleDocumentFactory
     * @param ProductDocumentFactory $productDocumentFactory
     * @param TopicDocumentFactory $topicDocumentFactory
     */
    public function __construct(
        RepositoryInterface $articleRepository,
        RepositoryInterface $productRepository,
        RepositoryInterface $topicRepository,
        Manager $manager,
        ArticleDocumentFactory $articleDocumentFactory,
        ProductDocumentFactory $productDocumentFactory,
        TopicDocumentFactory $topicDocumentFactory
    ) {
        $this->articleRepository = $articleRepository;
        $this->productRepository = $productRepository;
        $this->topicRepository = $topicRepository;
        $this->elasticsearchManager = $manager;
        $this->articleDocumentFactory = $articleDocumentFactory;
        $this->productDocumentFactory = $productDocumentFactory;
        $this->topicDocumentFactory = $topicDocumentFactory;

        parent::__construct('app:elastic-search:reset-index');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->addOption('force', 'f', null, 'To confirm running this command')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lockHandler = new LockHandler('sylius-elastic-index-update');

        if ($lockHandler->lock()) {
            if (!$input->getOption('force')) {
                $output->writeln('WARNING! This command will drop the existing index and rebuild it from scratch. To proceed, run with "--force" option.');

                return;
            }

            $output->writeln(sprintf('Dropping and creating "%s" ElasticSearch index', $this->elasticsearchManager->getIndexName()));
            $this->elasticsearchManager->dropAndCreateIndex();

            $this->resetItemsIndexes(
                $output,
                $this->articleRepository,
                $this->articleDocumentFactory,
                'Article',
                'articles'
            );

            $this->resetItemsIndexes(
                $output,
                $this->productRepository,
                $this->productDocumentFactory,
                'Product',
                'products'
            );

            $this->resetItemsIndexes(
                $output,
                $this->topicRepository,
                $this->topicDocumentFactory,
                'Topic',
                'topics'
            );

            $lockHandler->release();
        } else {
            $output->writeln(sprintf('<info>Command is already running</info>'));
        }
    }

    /**
     * @param OutputInterface $output
     * @param RepositoryInterface $repository
     * @param ArticleDocumentFactory|ProductDocumentFactory|TopicDocumentFactory $factory
     * @param string $itemName
     * @param string $itemPluralName
     *
     * @throws \ONGR\ElasticsearchBundle\Exception\BulkWithErrorsException
     */
    private function resetItemsIndexes(OutputInterface $output, RepositoryInterface $repository, $factory, string $itemName, string $itemPluralName)
    {
        $itemDocumentsCreated = 0;

        /** @var Topic[] $items */
        $items = $repository->findAll();

        $output->writeln(sprintf('Loading %d %s into ElasticSearch', count($items), $itemPluralName));

        foreach ($items as $item) {

            $itemDocument = $factory->create($item);

            $this->elasticsearchManager->persist($itemDocument);

            ++$itemDocumentsCreated;

            if (($itemDocumentsCreated % 100) === 0) {
                $this->elasticsearchManager->commit();
            }
        }

        $this->elasticsearchManager->commit();
        $output->writeln(sprintf('%s index was rebuilt!', $itemName));
    }
}