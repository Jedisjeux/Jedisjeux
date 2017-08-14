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

use AppBundle\Entity\Person;
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
     * @var ExampleFactoryInterface
     */
    protected $postFactory;

    /**
     * @var EntityRepository
     */
    protected $postRepository;

    /**
     * PersonContext constructor.
     *
     * @param ExampleFactoryInterface $postFactory
     * @param EntityRepository $postRepository
     */
    public function __construct(ExampleFactoryInterface $postFactory, EntityRepository $postRepository)
    {
        $this->postFactory = $postFactory;
        $this->postRepository = $postRepository;
    }

    /**
     * @Given customer :customer has answered the :topic topic
     *
     * @param CustomerInterface $customer
     * @param Topic $topic
     */
    public function CustomerHasAnsweredTheTopic(CustomerInterface $customer, Topic $topic)
    {
        /** @var Post $post */
        $post = $this->postFactory->create([
            'topic' => $topic,
            'author' => $customer,
        ]);

        $this->postRepository->add($post);
    }
}
