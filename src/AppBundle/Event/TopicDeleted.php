<?php

declare(strict_types=1);

namespace AppBundle\Event;

use AppBundle\Entity\Topic;

final class TopicDeleted
{
    /**
     * @var Topic
     */
    private $topic;

    /**
     * @param Topic $topic
     */
    private function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    /**
     * @param Topic $topic
     *
     * @return self
     */
    public static function occur(Topic $topic)
    {
        return new self($topic);
    }

    /**
     * @return Topic
     */
    public function topic(): Topic
    {
        return $this->topic;
    }
}
