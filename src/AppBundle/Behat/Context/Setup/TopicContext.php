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
use AppBundle\Entity\Topic;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    protected $topicFactory;

    /**
     * @var EntityRepository
     */
    protected $topicRepository;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $topicFactory
     * @param EntityRepository $topicRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $topicFactory,
        EntityRepository $topicRepository
    )
    {
        $this->sharedStorage = $sharedStorage;
        $this->topicFactory = $topicFactory;
        $this->topicRepository = $topicRepository;
    }


    /**
     * @Given there is topic with title :title written by :customer
     *
     * @param string $title
     * @param CustomerInterface $customer
     */
    public function thereIsTopicWithTitleWrittenByCustomer($title, CustomerInterface $customer)
    {
        /** @var Topic $topic */
        $topic = $this->topicFactory->create([
            'title' => $title,
            'author' => $customer,
        ]);

        $this->topicRepository->add($topic);
        $this->sharedStorage->set('topic', $topic);
    }
}
