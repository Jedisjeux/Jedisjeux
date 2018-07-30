<?php

namespace spec\AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Document\TopicDocument;
use AppBundle\Entity\Topic;
use AppBundle\Event\TopicCreated;
use AppBundle\Factory\Document\TopicDocumentFactory;
use AppBundle\Projection\TopicProjector;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TopicProjectorSpec extends ObjectBehavior
{
    function let(
        Manager $elasticsearchManager,
        TopicDocumentFactory $topicDocumentFactory,
        Repository $appDocumentRepository
    ) {
        $elasticsearchManager->getRepository(AppDocument::class)->willReturn($appDocumentRepository);

        $this->beConstructedWith($elasticsearchManager, $topicDocumentFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TopicProjector::class);
    }

    function it_saves_new_product_documents(
        Manager $elasticsearchManager,
        TopicDocumentFactory $topicDocumentFactory,
        Repository $productDocumentRepository,
        Topic $topic,
        AppDocument $appDocument
    ) {
        $topic->getCode()->willReturn('FOO');

        $existingProductDocument = new TopicDocument();
        $productDocumentRepository->findBy([
            'type' => AppDocument::TYPE_TOPIC,
            'code' => 'FOO'
        ])->willReturn(new \ArrayIterator([$existingProductDocument]));

        $topicDocumentFactory->create($topic)->willReturn($appDocument);

        $elasticsearchManager->persist(Argument::type(AppDocument::class))->shouldBeCalled();
        $elasticsearchManager->commit()->shouldBeCalled();

        $this->handleTopicCreated(TopicCreated::occur($topic->getWrappedObject()));
    }
}
