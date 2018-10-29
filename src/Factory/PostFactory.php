<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var CustomerContextInterface
     */
    protected $customerContext;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @param CustomerContextInterface $customerContext
     */
    public function setCustomerContext(CustomerContextInterface $customerContext)
    {
        $this->customerContext = $customerContext;
    }

    /**
     * @return Post
     */
    public function createNew()
    {
        /** @var Post $post */
        $post = new $this->className();
        $post->setAuthor($this->customerContext->getCustomer());

        return $post;
    }

    /**
     * @param Topic $topic
     *
     * @return Post
     */
    public function createForTopic($topic)
    {
        /** @var Post $post */
        $post = $this->createNew();

        $post
            ->setTopic($topic);

        return $post;
    }

    /**
     * @param GamePlay $gamePlay
     *
     * @return Post
     */
    public function createForGamePlay(GamePlay $gamePlay)
    {
        /** @var Post $post */
        $post = $this->createNew();

        $post
            ->setGamePlay($gamePlay);

        return $post;
    }

    /**
     * @param Article $article
     *
     * @return Post
     */
    public function createForArticle(Article $article)
    {
        /** @var Post $post */
        $post = $this->createNew();

        $post
            ->setArticle($article);

        return $post;
    }
}
