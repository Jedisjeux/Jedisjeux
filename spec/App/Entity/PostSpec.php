<?php

namespace spec\App\Entity;

use App\Entity\Post;
use App\Entity\Topic;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class PostSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Post::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_body_is_mutable()
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
        $topic->hasPost($this)->willReturn(false);

        $topic->addPost($this)->shouldBeCalled();

        $this->setTopic($topic);
        $this->getTopic()->shouldReturn($topic);
    }

    function its_author_is_mutable(CustomerInterface $author)
    {
        $this->setAuthor($author);
        $this->getAuthor()->shouldReturn($author);
    }
}
