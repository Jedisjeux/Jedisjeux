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
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostFactory extends Factory
{
    /**
     * @param int $topicId
     * @param EntityRepository $topicRepository
     *
     * @return Post
     */
    public function createNewWithTopic($topicId, EntityRepository $topicRepository)
    {
        /** @var Post $post */
        $post =  parent::createNew();

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
