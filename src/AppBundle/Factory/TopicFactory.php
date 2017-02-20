<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\Article;
use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicFactory implements FactoryInterface
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
     * @var EntityRepository
     */
    protected $gamePlayRepository;

    /**
     * @var FactoryInterface
     */
    protected $postFactory;

    /**
     * @param $className
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
     * @param EntityRepository $gamePlayRepository
     */
    public function setGamePlayRepository(EntityRepository $gamePlayRepository)
    {
        $this->gamePlayRepository = $gamePlayRepository;
    }

    /**
     * @param FactoryInterface $postFactory
     */
    public function setPostFactory(FactoryInterface $postFactory)
    {
        $this->postFactory = $postFactory;
    }

    /**
     * @return Topic
     */
    public function createNew()
    {
        /** @var Topic $topic */
        $topic = new $this->className;
        $topic->setAuthor($this->customerContext->getCustomer());

        /** @var Post $mainPost */
        $mainPost = $this->postFactory->createNew();
        $topic->setMainPost($mainPost);

        return $topic;
    }

    /**
     * @param GamePlay $gamePlay
     *
     * @return Topic
     */
    public function createForGamePlay(GamePlay $gamePlay)
    {
        /** @var Topic $topic */
        $topic = $this->createNew();
        $topic->setMainPost(null); // topic for game play has no main post

        $gamePlay
            ->setTopic($topic);

        $topic
            ->setTitle("Partie de ".(string) $gamePlay->getProduct())
            ->setAuthor($gamePlay->getAuthor());

        return $topic;
    }

    /**
     * @param Article $article
     *
     * @return Topic
     */
    public function createForArticle(Article $article)
    {
        /** @var Topic $topic */
        $topic = $this->createNew();
        $topic->setMainPost(null); // topic for article has no main post

        $article
            ->setTopic($topic);

        $topic
            ->setTitle($article->getTitle())
            ->setAuthor($article->getAuthor());

        return $topic;
    }
}
