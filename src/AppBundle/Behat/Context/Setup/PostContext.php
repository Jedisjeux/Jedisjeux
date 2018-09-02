<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Behat\Service\SharedStorageInterface;
use AppBundle\Entity\Article;
use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use AppBundle\Fixture\Factory\TopicExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    protected $postFactory;

    /**
     * @var ExampleFactoryInterface
     */
    protected $topicFactory;

    /**
     * @var EntityRepository
     */
    protected $postRepository;

    /**
     * @var EntityRepository
     */
    protected $topicRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $postFactory
     * @param ExampleFactoryInterface $topicFactory
     * @param EntityRepository $postRepository
     * @param EntityRepository $topicRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $postFactory,
        ExampleFactoryInterface $topicFactory,
        EntityRepository $postRepository,
        EntityRepository $topicRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->postFactory = $postFactory;
        $this->topicFactory = $topicFactory;
        $this->postRepository = $postRepository;
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Given /^(this topic) has(?:| also) a post added by (customer "[^"]+")$/
     * @Given /^I wrote a post to (this topic)$/
     *
     * @param Topic $topic
     * @param CustomerInterface $customer
     */
    public function topicHasAPostAddedByCustomer(Topic $topic, CustomerInterface $customer = null)
    {
        if (null === $customer) {
            $customer = $this->sharedStorage->get('customer');
        }

        /** @var Post $post */
        $post = $this->postFactory->create([
            'topic' => $topic,
            'author' => $customer,
        ]);

        $this->postRepository->add($post);
        $this->sharedStorage->set('post', $post);
    }

    /**
     * @Given /^(this article) has(?:| also) a comment added by (customer "[^"]+")$/
     * @Given /^I leaved a comment on (this article)$/
     *
     * @param Article $article
     * @param CustomerInterface $customer
     */
    public function articleHasACommentAddedByCustomer(Article $article, CustomerInterface $customer = null)
    {
        if (null === $customer) {
            $customer = $this->sharedStorage->get('customer');
        }

        if (null === $topic = $article->getTopic()) {
            /** @var Topic $topic */
            $topic = $this->topicFactory->create([
                'article' => $article,
            ]);

            $this->topicRepository->add($topic);
        }

        /** @var Post $post */
        $post = $this->postFactory->create([
            'article' => $article,
            'topic' => $topic,
            'author' => $customer,
        ]);

        $this->postRepository->add($post);
        $this->sharedStorage->set('comment', $post);
    }

    /**
     * @Given /^(this game play) has(?:| also) a comment added by (customer "[^"]+")$/
     * @Given /^I leaved a comment on (this game play)$/
     *
     * @param GamePlay $gamePlay
     * @param CustomerInterface $customer
     */
    public function gamePlayHasACommentAddedByCustomer(GamePlay $gamePlay, CustomerInterface $customer = null)
    {
        if (null === $customer) {
            $customer = $this->sharedStorage->get('customer');
        }

        if (null === $topic = $gamePlay->getTopic()) {
            /** @var Topic $topic */
            $topic = $this->topicFactory->create([
                'game_play' => $gamePlay,
            ]);

            $this->topicRepository->add($topic);
        }

        /** @var Post $post */
        $post = $this->postFactory->create([
            'game_play' => $gamePlay,
            'topic' => $topic,
            'author' => $customer,
        ]);

        $this->postRepository->add($post);
        $this->sharedStorage->set('comment', $post);
    }
}
