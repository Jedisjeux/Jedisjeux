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
use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
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
     * @var EntityRepository
     */
    protected $postRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $postFactory
     * @param EntityRepository $postRepository
     */
    public function __construct(SharedStorageInterface $sharedStorage, ExampleFactoryInterface $postFactory, EntityRepository $postRepository)
    {
        $this->sharedStorage = $sharedStorage;
        $this->postFactory = $postFactory;
        $this->postRepository = $postRepository;
    }

    /**
     * @Given /^(this topic) has(?:| also) a post added by (customer "[^"]+")$/
     *
     * @param Topic $topic
     * @param CustomerInterface $customer
     */
    public function topicHasAPostAddedByCustomer(Topic $topic, CustomerInterface $customer)
    {
        /** @var Post $post */
        $post = $this->postFactory->create([
            'topic' => $topic,
            'author' => $customer,
        ]);

        $this->postRepository->add($post);
        $this->sharedStorage->set('post', $post);
    }
}
