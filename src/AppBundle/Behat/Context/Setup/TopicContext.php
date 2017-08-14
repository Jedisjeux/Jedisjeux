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
use AppBundle\Entity\Topic;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    protected $topicFactory;

    /**
     * @var EntityRepository
     */
    protected $topicRepository;

    /**
     * PersonContext constructor.
     *
     * @param ExampleFactoryInterface $topicFactory
     * @param EntityRepository $topicRepository
     */
    public function __construct(ExampleFactoryInterface $topicFactory, EntityRepository $topicRepository)
    {
        $this->topicFactory = $topicFactory;
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Given there is topic with title :title written by author with email :customerEmail
     *
     * @param string $title
     * @param string $customerEmail
     */
    public function thereIsTopicWithTitleFromAuthorWithEmail($title, $customerEmail)
    {
        /** @var Topic $topic */
        $topic = $this->topicFactory->create([
            'title' => $title,
            'author' => $customerEmail,
        ]);

        $this->topicRepository->add($topic);
    }
}
