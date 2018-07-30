<?php

namespace spec\AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Document\PersonDocument;
use AppBundle\Entity\Person;
use AppBundle\Event\PersonCreated;
use AppBundle\Factory\Document\PersonDocumentFactory;
use AppBundle\Projection\PersonProjector;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PersonProjectorSpec extends ObjectBehavior
{
    function let(
        Manager $elasticsearchManager,
        PersonDocumentFactory $personDocumentFactory,
        Repository $appDocumentRepository
    ) {
        $elasticsearchManager->getRepository(AppDocument::class)->willReturn($appDocumentRepository);

        $this->beConstructedWith($elasticsearchManager, $personDocumentFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PersonProjector::class);
    }

    function it_saves_new_product_documents(
        Manager $elasticsearchManager,
        PersonDocumentFactory $personDocumentFactory,
        Repository $productDocumentRepository,
        Person $person,
        AppDocument $appDocument
    ) {
        $person->getCode()->willReturn('FOO');

        $existingProductDocument = new PersonDocument();
        $productDocumentRepository->findBy([
            'type' => AppDocument::TYPE_PERSON,
            'code' => 'FOO'
        ])->willReturn(new \ArrayIterator([$existingProductDocument]));

        $personDocumentFactory->create($person)->willReturn($appDocument);

        $elasticsearchManager->persist(Argument::type(AppDocument::class))->shouldBeCalled();
        $elasticsearchManager->commit()->shouldBeCalled();

        $this->handlePersonCreated(PersonCreated::occur($person->getWrappedObject()));
    }
}
