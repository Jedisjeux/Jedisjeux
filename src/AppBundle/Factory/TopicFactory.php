<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/03/16
 * Time: 08:22
 */

namespace AppBundle\Factory;

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\Topic;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicFactory extends Factory
{
    /**
     * @var EntityRepository
     */
    protected $gamePlayRepository;

    /**
     * @param EntityRepository $gamePlayRepository
     */
    public function setGamePlayRepository($gamePlayRepository)
    {
        $this->gamePlayRepository = $gamePlayRepository;
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
        $topic = parent::createNew();
        $topic->setGamePlay($gamePlay);

        return $topic;
    }
}