<?php

namespace spec\AppBundle\Factory\Document;

use AppBundle\Document\AppDocument;
use AppBundle\Document\TopicDocument;
use AppBundle\Entity\Topic;
use AppBundle\Factory\Document\TopicDocumentFactory;
use PhpSpec\ObjectBehavior;

class TopicDocumentFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            AppDocument::class,
            TopicDocument::class
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TopicDocumentFactory::class);
    }

    function it_creates_document_from_topic(Topic $topic): void
    {
        $topic->getId()->willReturn(1);
        $topic->getTitle()->willReturn("New message");
        $topic->getCreatedAt()->willReturn(new \DateTime());

        $document = $this->create($topic);
        $document->shouldHaveType(AppDocument::class);
        $document->getTopic()->shouldHaveType(TopicDocument::class);
    }
}
