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
     * @var FeedDumpService
     */
    protected $feedDump;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * ArticleFeedDump constructor.
     *
     * @param FeedDumpService  $feedDump
     * @param EntityRepository $repository
     * @param string           $rootDir
     * @param string           $fileName
     */
    public function __construct(FeedDumpService $feedDump, EntityRepository $repository, $rootDir, $fileName)
    {
        $this->feedDump = $feedDump;
        $this->repository = $repository;
        $this->rootDir = $rootDir;
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Dumps article feed.
     */
    public function dump()
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
    protected function findPublishedArticles()
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
