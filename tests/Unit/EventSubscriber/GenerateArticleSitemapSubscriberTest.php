<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\Entity\Article;
use App\EventSubscriber\GenerateArticleSitemapSubscriber;
use App\Repository\ArticleRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class GenerateArticleSitemapSubscriberTest extends TestCase
{
    private UrlGeneratorInterface&MockObject $urlGenerator;

    private ArticleRepository&MockObject $articleRepository;

    private EntityManagerInterface&MockObject $entityManager;

    private GenerateArticleSitemapSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->articleRepository = $this->createMock(ArticleRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->subscriber = new GenerateArticleSitemapSubscriber(
            $this->urlGenerator,
            $this->articleRepository,
            $this->entityManager,
        );
    }

    public function testItIsASubscriber(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItSubscribesToEvents(): void
    {
        $this->assertSame([
            SitemapPopulateEvent::class => 'populate',
        ], GenerateArticleSitemapSubscriber::getSubscribedEvents());
    }

    public function testItRegistersProductsUrls(): void
    {
        $firstArticle = $this->createStub(Article::class);
        $firstArticle->method('getSlug')->willReturn('news-one');
        $firstArticle->method('getUpdatedAt')->willReturn(new \DateTime());
        $secondArticle = $this->createStub(Article::class);
        $secondArticle->method('getSlug')->willReturn('news-two');
        $secondArticle->method('getUpdatedAt')->willReturn(new \DateTime());

        $query = $this->createStub(AbstractQuery::class);
        $query->method('iterate')->willReturn([[$firstArticle], [$secondArticle]]);
        $queryBuilder = $this->createStub(QueryBuilder::class);
        $queryBuilder->method('getQuery')->willReturn($query);
        $this->articleRepository->method('createQueryBuilder')->with('o')->willReturn($queryBuilder);

        $this->urlGenerator->expects($this->exactly(2))->method('generate')->willReturnMap([
            ['app_frontend_article_show', ['slug' => 'news-one'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/article/news-one'],
            ['app_frontend_article_show', ['slug' => 'news-two'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/article/news-two'],
        ]);

        $urls = $this->createMock(UrlContainerInterface::class);
        $urls->expects($this->exactly(2))->method('addUrl');

        $this->entityManager->expects($this->exactly(2))->method('clear');

        $this->subscriber->populate(new SitemapPopulateEvent($urls, $this->urlGenerator));
    }
}
