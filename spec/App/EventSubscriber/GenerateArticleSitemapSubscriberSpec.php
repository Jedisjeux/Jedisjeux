<?php

namespace spec\App\EventSubscriber;

use App\Entity\Article;
use App\EventSubscriber\GenerateArticleSitemapSubscriber;
use App\Repository\ArticleRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateArticleSitemapSubscriberSpec extends ObjectBehavior
{
    function let(
        UrlGeneratorInterface $urlGenerator,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager
    ): void {
        $this->beConstructedWith($urlGenerator, $articleRepository, $entityManager);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(GenerateArticleSitemapSubscriber::class);
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
        ArticleRepository $articleRepository,
        QueryBuilder $queryBuilder,
        AbstractQuery $query,
        Article $firstArticle,
        Article $secondArticle,
        UrlGeneratorInterface $urlGenerator,
        EntityManagerInterface $entityManager
    ): void {
        $event->getUrlContainer()->willReturn($urls);
        $articleRepository->createQueryBuilder('o')->willReturn($queryBuilder);

        $query->iterate()->willReturn([
            [
                $firstArticle->getWrappedObject(),
            ],
            [
                $secondArticle->getWrappedObject(),
            ],
        ]);

        $queryBuilder->getQuery()->willReturn($query);
        $firstArticle->getSlug()->willReturn('news-one');
        $secondArticle->getSlug()->willReturn('news-two');
        $firstArticle->getUpdatedAt()->willReturn(new \DateTime());
        $secondArticle->getUpdatedAt()->willReturn(new \DateTime());

        $urlGenerator->generate(
            'sylius_frontend_product_show',
            ['slug' => 'news-one'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->willReturn('http://example.com/article/news-one');
        $urlGenerator->generate(
            'sylius_frontend_product_show',
            ['slug' => 'news-two'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->willReturn('http://example.com/article/news-two');

        $urlGenerator->generate(
            'app_frontend_article_show',
            ['slug' => 'news-one'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->shouldBeCalled();
        $urlGenerator->generate(
            'app_frontend_article_show',
            ['slug' => 'news-two'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->shouldBeCalled();

        $urls->addUrl(Argument::cetera())->shouldBeCalled();

        $entityManager->clear(Argument::cetera())->shouldBeCalledTimes(2);

        $this->populate($event);
    }
}
