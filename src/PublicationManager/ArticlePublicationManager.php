<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\PublicationManager;

use App\Entity\Article;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticlePublicationManager
{
    /**
     * ArticlePublicationManager constructor.
     */
    public function __construct(private ObjectManager $manager)
    {
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function start(Article $article): void
    {
        $article->setPublishStartDate(new \DateTime());

        $this->manager->flush();
    }
}
