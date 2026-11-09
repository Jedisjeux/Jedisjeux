<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use App\Fixture\Factory\GamePlayExampleFactory;
use App\Fixture\Factory\PostExampleFactory;
use App\Fixture\Factory\TopicExampleFactory;
use Behat\Behat\Context\Context;
use App\Tests\Behat\Bridge\Service\SharedStorageInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var GamePlayExampleFactory
     */
    protected $gamePlayFactory;

    /**
     * @var TopicExampleFactory
     */
    protected $topicFactory;

    /**
     * @var PostExampleFactory
     */
    protected $postFactory;

    /**
     * @var RepositoryInterface
     */
    protected $gamePlayRepository;

    /**
     * @var RepositoryInterface
     */
    protected $topicRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param GamePlayExampleFactory $gamePlayFactory
     * @param TopicExampleFactory    $topicFactory
     * @param PostExampleFactory     $postFactory
     * @param RepositoryInterface    $gamePlayRepository
     * @param RepositoryInterface    $topicRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        GamePlayExampleFactory $gamePlayFactory,
        TopicExampleFactory $topicFactory,
        PostExampleFactory $postFactory,
        RepositoryInterface $gamePlayRepository,
        RepositoryInterface $topicRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->gamePlayFactory = $gamePlayFactory;
        $this->topicFactory = $topicFactory;
        $this->postFactory = $postFactory;
        $this->gamePlayRepository = $gamePlayRepository;
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Given /^(this product) has(?:| also) one game play from (customer "[^"]+")$/
     * @Given /^(this product) has(?:| also) a game play added by (customer "[^"]+")(?:|, created (\d+) days ago)$/
     * @Given /^(this product) has(?:| also) one game play written by me$/
     *
     * @param ProductInterface  $product
     * @param CustomerInterface $customer
     */
    public function productHasAGamePlay(ProductInterface $product, ?CustomerInterface $customer = null, $daysSinceCreation = null)
    {
        if (null === $customer) {
            $customer = $this->sharedStorage->get('customer');
        }

        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayFactory->create([
            'product' => $product,
            'author' => $customer,
            'created_at' => 'now',
        ]);

        if (null !== $daysSinceCreation) {
            $gamePlay->setCreatedAt(new \DateTime('-'.$daysSinceCreation.' days'));
        }

        $this->gamePlayRepository->add($gamePlay);
        $this->sharedStorage->set('game_play', $gamePlay);
    }

    /**
     * @Given /^I played (product "[^"]+") (once|\d+ times) in (\d{4})$/
     */
    public function iPlayedProductInYear(ProductInterface $product, string $times, int $year): void
    {
        $this->customerPlayedProductInYear($this->sharedStorage->get('customer'), $product, $times, $year);
    }

    /**
     * @Given /^(customer "[^"]+") played (product "[^"]+") (once|\d+ times) in (\d{4})$/
     */
    public function customerPlayedProductInYear(CustomerInterface $customer, ProductInterface $product, string $times, int $year): void
    {
        $count = 'once' === $times ? 1 : (int) $times;

        for ($i = 0; $i < $count; ++$i) {
            /** @var GamePlay $gamePlay */
            $gamePlay = $this->gamePlayFactory->create([
                'product' => $product,
                'author' => $customer,
                // Spread over the year, without crossing its bounds
                'played_at' => sprintf('%d-%02d-15', $year, $i % 12 + 1),
                'created_at' => 'now',
            ]);

            $this->gamePlayRepository->add($gamePlay);
        }
    }

    /**
     * @Given /^(this product) has one game play from (customer "[^"]+") with (\d+) comments$/
     * @Given /^(this product) has(?:| also) a game play added by (customer "[^"]+") with (\d+) comments(?:|, created (\d+) days ago)$/
     *
     * @param ProductInterface  $product
     * @param CustomerInterface $customer
     * @param int               $postCount
     */
    public function thisProductHasAGamePlayWithComments(ProductInterface $product, CustomerInterface $customer, $postCount, $daysSinceCreation = null)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayFactory->create([
            'product' => $product,
            'author' => $customer,
            'created_at' => 'now',
        ]);

        if (null !== $daysSinceCreation) {
            $gamePlay->setCreatedAt(new \DateTime('-'.$daysSinceCreation.' days'));
        }

        $this->gamePlayRepository->add($gamePlay);

        /** @var Topic $topic */
        $topic = $this->topicFactory->create([
            'game_play' => $gamePlay,
        ]);

        $this->createPostsForTopic($topic, $postCount);
        $this->gamePlayRepository->add($topic);
        $this->sharedStorage->set('game_play', $gamePlay);
    }

    /**
     * @param Topic $topic
     * @param int   $amount
     */
    private function createPostsForTopic(Topic $topic, $amount)
    {
        for ($i = 0; $i < $amount; ++$i) {
            /** @var Post $post */
            $post = $this->postFactory->create([
                'topic' => $topic,
            ]);

            $topic->addPost($post);
        }
    }
}
