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

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostFactory implements FactoryInterface
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
     * @return Post
     */
    public function createNew()
    {
        /** @var Post $post */
        $post = $this->factory->createNew();
        $post->setAuthor($this->customerContext->getCustomer());

        return $post;
    }

    /**
     * @param int $topicId
     * @param EntityRepository $topicRepository
     *
     * @return Post
     */
    public function createForTopic($topicId, EntityRepository $topicRepository)
    {
        /** @var Post $post */
        $post =  $this->createNew();

        /** @var Topic $topic */
        $topic = $topicRepository->find($topicId);

        if (null === $topic) {
            throw new NotFoundHttpException(sprintf('Topic with id %s not found', $topicId));
        }

        $post
            ->setTopic($topic);

        return $post;
    }
}
