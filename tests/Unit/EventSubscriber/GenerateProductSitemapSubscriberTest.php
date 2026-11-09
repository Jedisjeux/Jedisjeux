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

use App\Entity\ProductInterface;
use App\EventSubscriber\GenerateProductSitemapSubscriber;
use App\Repository\ProductRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class GenerateProductSitemapSubscriberTest extends TestCase
{
    private UrlGeneratorInterface&MockObject $urlGenerator;

    private ProductRepository&MockObject $productRepository;

    private EntityManagerInterface&MockObject $entityManager;

    private GenerateProductSitemapSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->subscriber = new GenerateProductSitemapSubscriber(
            $this->urlGenerator,
            $this->productRepository,
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
        ], GenerateProductSitemapSubscriber::getSubscribedEvents());
    }

    public function testItRegistersProductsUrls(): void
    {
        $firstProduct = $this->createStub(ProductInterface::class);
        $firstProduct->method('getSlug')->willReturn('puerto-rico');
        $firstProduct->method('getUpdatedAt')->willReturn(new \DateTime());
        $secondProduct = $this->createStub(ProductInterface::class);
        $secondProduct->method('getSlug')->willReturn('modern-art');
        $secondProduct->method('getUpdatedAt')->willReturn(new \DateTime());

        $query = $this->createStub(AbstractQuery::class);
        $query->method('iterate')->willReturn([[$firstProduct], [$secondProduct]]);
        $queryBuilder = $this->createStub(QueryBuilder::class);
        $queryBuilder->method('getQuery')->willReturn($query);
        $this->productRepository->method('createQueryBuilder')->with('o')->willReturn($queryBuilder);

        $this->urlGenerator->expects($this->exactly(2))->method('generate')->willReturnMap([
            ['sylius_frontend_product_show', ['slug' => 'puerto-rico'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/game/puerto-rico'],
            ['sylius_frontend_product_show', ['slug' => 'modern-art'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/game/modern-art'],
        ]);

        $urls = $this->createMock(UrlContainerInterface::class);
        $urls->expects($this->exactly(2))->method('addUrl');

        $this->entityManager->expects($this->exactly(2))->method('clear');

        $this->subscriber->populate(new SitemapPopulateEvent($urls, $this->urlGenerator));
    }
}
