<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\Post;
use AppBundle\Entity\Topic;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\CustomerInterface;

class PostSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Post::class);
    }

    function it_sets_body()
    {
        $this->setBody('<p>Subject body</p>');

        $this->getBody()->shouldReturn('<p>Subject body</p>');
    }

    function its_parent_is_mutable(Topic $topic)
    {
        $this->setParent($topic);
        $this->getParent()->shouldReturn($topic);
    }

    function its_topic_is_mutable(Topic $topic)
    {
        $this->setTopic($topic);
        $this->getTopic()->shouldReturn($topic);
    }

    function its_author_is_mutable(CustomerInterface $author)
    {
        $this->setAuthor($author);
        $this->getAuthor()->shouldReturn($author);
    }
}
