<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Feed;

use App\Entity\Article;
use Doctrine\ORM\EntityRepository;
use Eko\FeedBundle\Service\FeedDumpService;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleFeedDump
{
    /**
     * ArticleFeedDump constructor.
     *
     * @param string $rootDir
     * @param string $fileName
     */
    public function __construct(
        private FeedDumpService $feedDump,
        private EntityRepository $repository,
        private $rootDir,
        private $fileName
    ) {
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Dumps article feed.
     */
    public function dump(): void
    {
        $articles = $this->findPublishedArticles();

        $this->feedDump
            ->setName('article')
            ->setFilename($this->fileName)
            ->setFormat('rss')
            ->setItems($articles)
            ->setRootDir($this->rootDir);

        $this->feedDump->dump();
    }

    /**
     * @return array
     */
    private function findPublishedArticles()
    {
        $queryBuilder = $this->repository->createQueryBuilder('o');
        $queryBuilder
            ->andWhere('o.status = :published')
            ->orderBy('o.publishStartDate', 'desc')
            ->setMaxResults(20)
            ->setParameter('published', Article::STATUS_PUBLISHED);

        return $queryBuilder->getQuery()->getResult();
    }
}
