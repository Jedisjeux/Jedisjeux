<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use App\Fixture\Factory\PostExampleFactory;
use App\Fixture\Factory\TopicExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

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
     * @var PostExampleFactory
     */
    protected $postFactory;

    /**
     * @var TopicExampleFactory
     */
    protected $topicFactory;

    /**
     * @var RepositoryInterface
     */
    protected $postRepository;

    /**
     * @var RepositoryInterface
     */
    protected $topicRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param PostExampleFactory     $postFactory
     * @param TopicExampleFactory    $topicFactory
     * @param RepositoryInterface    $postRepository
     * @param RepositoryInterface    $topicRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        PostExampleFactory $postFactory,
        TopicExampleFactory $topicFactory,
        RepositoryInterface $postRepository,
        RepositoryInterface $topicRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->postFactory = $postFactory;
        $this->topicFactory = $topicFactory;
        $this->postRepository = $postRepository;
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Given /^(this topic) has(?:| also) a post added by (customer "[^"]+")$/
     * @Given /^(this topic) has(?:| also) a post added by (customer "[^"]+"), created "[^"]+"$/
     * @Given /^I wrote a post to (this topic)$/
     */
    public function topicHasAPostAddedByCustomer(Topic $topic, CustomerInterface $customer = null, $date = 'now')
    {
        if (null === $customer) {
            $customer = $this->sharedStorage->get('customer');
        }

        /** @var Post $post */
        $post = $this->postFactory->create([
            'topic' => $topic,
            'author' => $customer,
            'created_at' => $date,
        ]);

        $this->postRepository->add($post);
        $this->sharedStorage->set('post', $post);
    }

    /**
     * @Given /^(customer "[^"]+") has left a comment ("[^"]+") on (this topic)$/
     * @Given /^(customer "[^"]+") has left a comment ("[^"]+") on (this topic) ("[^"]+")$/
     */
    public function customerHasLeftACommentOnTopic(CustomerInterface $customer, string $comment, Topic $topic, $date = 'now')
    {
        /** @var Post $post */
        $post = $this->postFactory->create([
            'body' => $comment,
            'topic' => $topic,
            'author' => $customer,
            'created_at' => $date,
        ]);

        $this->postRepository->add($post);
        $this->sharedStorage->set('post', $post);
    }

    /**
     * @Given /^(this article) has(?:| also) a comment added by (customer "[^"]+")$/
     * @Given /^I leaved a comment on (this article)$/
     *
     * @param Article           $article
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
     * @param GamePlay          $gamePlay
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
