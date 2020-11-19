<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\PublicationManager;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticlePublicationManager
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * ArticlePublicationManager constructor.
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function start(Article $article)
    {
        $article->setPublishStartDate(new \DateTime());

        $this->manager->flush();
    }
}
