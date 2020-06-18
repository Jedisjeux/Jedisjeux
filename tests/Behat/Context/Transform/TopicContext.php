<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Transform;

use App\Entity\Topic;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
final class TopicContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $topicRepository;

    /**
     * PersonContext constructor.
     *
     * @param RepositoryInterface $topicRepository
     */
    public function __construct(RepositoryInterface $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Transform /^topic "([^"]+)"$/
     * @Transform :topic
     *
     * @param string $title
     *
     * @return Topic
     */
    public function getTopicByTitle($title)
    {
        /** @var Topic $topic */
        $topic = $this->topicRepository->findOneBy(['title' => $title]);

        Assert::notNull(
            $topic,
            sprintf('Topic with title "%s" does not exist', $title)
        );

        return $topic;
    }
}
