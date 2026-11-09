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

use App\Entity\Taxon;
use App\EventSubscriber\GenerateProductTaxonSitemapSubscriber;
use App\Repository\TaxonRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class GenerateProductTaxonSitemapSubscriberTest extends TestCase
{
    private UrlGeneratorInterface&MockObject $urlGenerator;

    private TaxonRepository&Stub $taxonRepository;

    private GenerateProductTaxonSitemapSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->taxonRepository = $this->createStub(TaxonRepository::class);
        $this->subscriber = new GenerateProductTaxonSitemapSubscriber($this->urlGenerator, $this->taxonRepository);
    }

    public function testItIsASubscriber(): void
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    public function testItSubscribesToEvents(): void
    {
        $this->assertSame([
            SitemapPopulateEvent::class => 'populate',
        ], GenerateProductTaxonSitemapSubscriber::getSubscribedEvents());
    }

    public function testItRegistersProductTaxonsUrls(): void
    {
        $mechanismTaxon = $this->createStub(TaxonInterface::class);
        $mechanismTaxon->method('getCode')->willReturn(Taxon::CODE_THEME);
        $themeTaxon = $this->createStub(TaxonInterface::class);
        $themeTaxon->method('getCode')->willReturn(Taxon::CODE_MECHANISM);
        $firstTaxon = $this->createStub(TaxonInterface::class);
        $firstTaxon->method('getSlug')->willReturn('mechanisms/draft');
        $secondTaxon = $this->createStub(TaxonInterface::class);
        $secondTaxon->method('getSlug')->willReturn('themes/western');

        $this->taxonRepository->method('findRootNodes')->willReturn([$mechanismTaxon, $themeTaxon]);
        $this->taxonRepository->method('findChildrenAsTree')->willReturnMap([
            [$mechanismTaxon, false, null, [$firstTaxon]],
            [$themeTaxon, false, null, [$secondTaxon]],
        ]);

        $this->urlGenerator->expects($this->exactly(2))->method('generate')->willReturnMap([
            ['sylius_frontend_product_index_by_taxon', ['slug' => 'mechanisms/draft'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/game/puerto-rico'],
            ['sylius_frontend_product_index_by_taxon', ['slug' => 'themes/western'], UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/game/modern-art'],
        ]);

        $urls = $this->createMock(UrlContainerInterface::class);
        $urls->expects($this->exactly(2))->method('addUrl');

        $this->subscriber->populate(new SitemapPopulateEvent($urls, $this->urlGenerator));
    }
}
