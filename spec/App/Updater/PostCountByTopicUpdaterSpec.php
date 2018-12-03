<?php

namespace spec\App\Updater;

use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;

class PostCountByTopicUpdaterSpec extends ObjectBehavior
{
    function it_updates_topic_with_post_count(
        Topic $topic,
        Post $firstPost,
        Post $secondPost
    ): void {
        $topic->getPosts()->willReturn(new ArrayCollection([
            $firstPost->getWrappedObject(),
            $secondPost->getWrappedObject(),
        ]));

        $topic->setPostCount(2)->shouldBeCalled();

        $this->update($topic);
    }
}
