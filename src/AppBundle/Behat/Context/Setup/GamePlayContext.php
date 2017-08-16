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

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    protected $gamePlayFactory;

    /**
     * @var ExampleFactoryInterface
     */
    protected $topicFactory;

    /**
     * @var ExampleFactoryInterface
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
     * PersonContext constructor.
     *
     * @param ExampleFactoryInterface $gamePlayFactory
     * @param ExampleFactoryInterface $topicFactory
     * @param ExampleFactoryInterface $postFactory
     * @param RepositoryInterface $gamePlayRepository
     * @param RepositoryInterface $topicRepository
     */
    public function __construct(
        ExampleFactoryInterface $gamePlayFactory,
        ExampleFactoryInterface $topicFactory,
        ExampleFactoryInterface $postFactory,
        RepositoryInterface $gamePlayRepository,
        RepositoryInterface $topicRepository
    )
    {
        $this->gamePlayFactory = $gamePlayFactory;
        $this->topicFactory = $topicFactory;
        $this->postFactory = $postFactory;
        $this->gamePlayRepository = $gamePlayRepository;
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Given there is game play of :product played by :customer
     *
     * @param ProductInterface $product
     * @param CustomerInterface $customer
     */
    public function thereIsGamePlayOfProductPlayedByCustomer(ProductInterface $product, CustomerInterface $customer)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayFactory->create([
            'product' => $product,
            'author' => $customer,
        ]);

        $this->gamePlayRepository->add($gamePlay);
    }

    /**
     * @Given there is game play of :product played by :customer with :postCount comments
     *
     * @param ProductInterface $product
     * @param CustomerInterface $customer
     * @param int $postCount
     */
    public function thereIsGamePlayOfProductPlayedByCustomerWithComments(ProductInterface $product, CustomerInterface $customer, $postCount)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayFactory->create([
            'product' => $product,
            'author' => $customer,
        ]);

        $this->gamePlayRepository->add($gamePlay);

        /** @var Topic $topic */
        $topic = $this->topicFactory->create([
            'game_play' => $gamePlay,
        ]);

        $this->createPostsForTopic($topic, $postCount);
        $this->gamePlayRepository->add($topic);
    }

    private function createPostsForTopic(Topic $topic, $amount)
    {
        for ($i = 0 ; $i < $amount ; $i++) {
            /** @var Post $post */
            $post = $this->postFactory->create([
                'topic' => $topic,
            ]);

            $topic->addPost($post);
        }
    }
}
