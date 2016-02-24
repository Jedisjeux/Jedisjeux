<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/02/2016
 * Time: 13:20
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