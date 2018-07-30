<?php

namespace spec\AppBundle\Projection;

use AppBundle\Document\AppDocument;
use AppBundle\Document\ArticleDocument;
use AppBundle\Entity\Article;
use AppBundle\Event\ArticleCreated;
use AppBundle\Factory\Document\ArticleDocumentFactory;
use AppBundle\Projection\ArticleProjector;
use ONGR\ElasticsearchBundle\Service\Manager;
use ONGR\ElasticsearchBundle\Service\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticleProjectorSpec extends ObjectBehavior
{
    function let(
        Manager $elasticsearchManager,
        ArticleDocumentFactory $articleDocumentFactory,
        Repository $appDocumentRepository
    ) {
        $elasticsearchManager->getRepository(AppDocument::class)->willReturn($appDocumentRepository);

        $this->beConstructedWith($elasticsearchManager, $articleDocumentFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArticleProjector::class);
    }

    function it_saves_new_product_documents(
        Manager $elasticsearchManager,
        ArticleDocumentFactory $articleDocumentFactory,
        Repository $productDocumentRepository,
        Article $article,
        AppDocument $appDocument
    ) {
        $article->getCode()->willReturn('FOO');

        $existingProductDocument = new ArticleDocument();
        $productDocumentRepository->findBy([
            'type' => AppDocument::TYPE_ARTICLE,
            'code' => 'FOO'
        ])->willReturn(new \ArrayIterator([$existingProductDocument]));

        $articleDocumentFactory->create($article)->willReturn($appDocument);

        $elasticsearchManager->persist(Argument::type(AppDocument::class))->shouldBeCalled();
        $elasticsearchManager->commit()->shouldBeCalled();

        $this->handleArticleCreated(ArticleCreated::occur($article->getWrappedObject()));
    }
}
