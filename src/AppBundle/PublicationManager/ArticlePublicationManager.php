<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\PublicationManager;

use AppBundle\Entity\Article;
use Doctrine\ORM\EntityManager;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticlePublicationManager
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * ArticlePublicationManager constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Article $article
     */
    public function start(Article $article)
    {
        $article->setPublishStartDate(new \DateTime());

        $this->manager->flush();
    }
}
