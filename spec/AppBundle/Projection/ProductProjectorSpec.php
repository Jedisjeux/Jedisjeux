<?php

declare(strict_types=1);

namespace spec\AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Entity\Product;
use AppBundle\Factory\Document\ProductDocumentFactory;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;
use PhpSpec\ObjectBehavior;
use AppBundle\Document\ProductDocument;
use AppBundle\Event\ProductCreated;
use AppBundle\Projection\ProductProjector;
use Prophecy\Argument;

final class ProductProjectorSpec extends ObjectBehavior
{
    function let(
        Manager $elasticsearchManager,
        ProductDocumentFactory $productDocumentFactory,
        Repository $appDocumentRepository
    ) {
        $elasticsearchManager->getRepository(AppDocument::class)->willReturn($appDocumentRepository);

        $this->beConstructedWith($elasticsearchManager, $productDocumentFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductProjector::class);
    }

    function it_saves_new_product_documents(
        Manager $elasticsearchManager,
        ProductDocumentFactory $productDocumentFactory,
        Repository $productDocumentRepository,
        Product $product,
        AppDocument $appDocument
    ) {
        $product->getCode()->willReturn('FOO');

        $existingProductDocument = new ProductDocument();
        $productDocumentRepository->findBy([
            'type' => AppDocument::TYPE_PRODUCT,
            'code' => 'FOO'
        ])->willReturn(new \ArrayIterator([$existingProductDocument]));

        $productDocumentFactory->create($product)->willReturn($appDocument);

        $elasticsearchManager->persist(Argument::type(AppDocument::class))->shouldBeCalled();
        $elasticsearchManager->commit()->shouldBeCalled();

        $this->handleProductCreated(ProductCreated::occur($product->getWrappedObject()));
    }
}
