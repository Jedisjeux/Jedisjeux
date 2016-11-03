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
     * @var FactoryInterface
     */
    private $factory;

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
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
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
        $topic = $this->factory->createNew();
        $topic->setAuthor($this->customerContext->getCustomer());

        /** @var Post $mainPost */
        $mainPost = $this->postFactory->createNew();
        $topic->setMainPost($mainPost);

        return $topic;
    }

    /**
     * @param int $gamePlayId
     *
     * @return Topic
     */
    public function createForGamePlay($gamePlayId)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayRepository->find($gamePlayId);

        if (null === $gamePlay) {
            throw new \InvalidArgumentException(sprintf('Requested gameplay does not exist with id "%s".', $gamePlayId));
        }

        /** @var Topic $topic */
        $topic = $this->createNew();
        $topic->setMainPost(null); // topic for game play has no main post

        $gamePlay
            ->setTopic($topic);

        $topic
            ->setTitle("Partie de ".(string)$gamePlay->getProduct())
            ->setAuthor($gamePlay->getAuthor());

        return $topic;
    }
}
