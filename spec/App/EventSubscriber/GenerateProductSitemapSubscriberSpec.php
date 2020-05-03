<?php

namespace spec\App\EventSubscriber;

use App\Entity\ProductInterface;
use App\EventSubscriber\GenerateProductSitemapSubscriber;
use App\Repository\ProductRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateProductSitemapSubscriberSpec extends ObjectBehavior
{
    function let(
        UrlGeneratorInterface $urlGenerator,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): void {
        $this->beConstructedWith($urlGenerator, $productRepository, $entityManager);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(GenerateProductSitemapSubscriber::class);
    }

    function it_is_a_subscriber(): void
    {
        $this->shouldImplement(EventSubscriberInterface::class);
    }

    function it_subscribes_to_events(): void
    {
        $this::getSubscribedEvents()->shouldReturn([
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
        ]);
    }

    function it_registers_products_urls(
        SitemapPopulateEvent $event,
        UrlContainerInterface $urls,
        ProductRepository $productRepository,
        QueryBuilder $queryBuilder,
        ProductInterface $firstProduct,
        ProductInterface $secondProduct,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ): void {
        $event->getUrlContainer()->willReturn($urls);
        $productRepository->createQueryBuilder('o')->willReturn($queryBuilder);

        $query = \Mockery::mock(AbstractQuery::class);
        $query->shouldReceive('iterate')->andReturn([
            [
                $firstProduct->getWrappedObject(),
            ],
            [
                $secondProduct->getWrappedObject(),
            ],
        ]);

        $queryBuilder->getQuery()->willReturn($query);
        $firstProduct->getSlug()->willReturn('puerto-rico');
        $secondProduct->getSlug()->willReturn('modern-art');
        $firstProduct->getUpdatedAt()->willReturn(new \DateTime());
        $secondProduct->getUpdatedAt()->willReturn(new \DateTime());

        $urlGenerator->generate(
            'sylius_frontend_product_show',
            ['slug' => 'puerto-rico'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->willReturn('http://example.com/game/puerto-rico');
        $urlGenerator->generate(
            'sylius_frontend_product_show',
            ['slug' => 'modern-art'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->willReturn('http://example.com/game/modern-art');

        $urlGenerator->generate(
            'sylius_frontend_product_show',
            ['slug' => 'puerto-rico'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->shouldBeCalled();
        $urlGenerator->generate(
            'sylius_frontend_product_show',
            ['slug' => 'modern-art'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->shouldBeCalled();

        $urls->addUrl(Argument::cetera())->shouldBeCalled();

        $entityManager->clear(Argument::cetera())->shouldBeCalledTimes(2);

        $this->populate($event);
    }
}
