<?php

namespace spec\App\EventSubscriber;

use App\Entity\Taxon;
use App\EventSubscriber\GenerateProductTaxonSitemapSubscriber;
use App\Repository\TaxonRepository;
use PhpSpec\ObjectBehavior;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Prophecy\Argument;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateProductTaxonSitemapSubscriberSpec extends ObjectBehavior
{
    function let(
        UrlGeneratorInterface $urlGenerator,
        TaxonRepository $taxonRepository
    ): void {
        $this->beConstructedWith($urlGenerator, $taxonRepository);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(GenerateProductTaxonSitemapSubscriber::class);
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

    function it_registers_product_taxons_urls(
        SitemapPopulateEvent $event,
        UrlContainerInterface $urls,
        TaxonRepository $taxonRepository,
        TaxonInterface $mechanismTaxon,
        TaxonInterface $themeTaxon,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        UrlGeneratorInterface $urlGenerator
    ): void {
        $event->getUrlContainer()->willReturn($urls);
        $taxonRepository->findRootNodes()->willReturn([
            $mechanismTaxon->getWrappedObject(),
            $themeTaxon->getWrappedObject(),
        ]);

        $mechanismTaxon->getCode()->willReturn(Taxon::CODE_THEME);
        $themeTaxon->getCode()->willReturn(Taxon::CODE_MECHANISM);

        $taxonRepository->findChildrenAsTree($mechanismTaxon, false)->willReturn([
            $firstTaxon->getWrappedObject(),
        ]);

        $taxonRepository->findChildrenAsTree($themeTaxon, false)->willReturn([
            $secondTaxon->getWrappedObject(),
        ]);

        $firstTaxon->getSlug()->willReturn('mechanisms/draft');
        $secondTaxon->getSlug()->willReturn('themes/western');

        $urlGenerator->generate(
            'sylius_frontend_product_index_by_taxon',
            ['slug' => 'mechanisms/draft'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->willReturn('http://example.com/game/puerto-rico');
        $urlGenerator->generate(
            'sylius_frontend_product_index_by_taxon',
            ['slug' => 'themes/western'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->willReturn('http://example.com/game/modern-art');

        $urlGenerator->generate(
            'sylius_frontend_product_index_by_taxon',
            ['slug' => 'mechanisms/draft'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->shouldBeCalled();
        $urlGenerator->generate(
            'sylius_frontend_product_index_by_taxon',
            ['slug' => 'themes/western'],
            UrlGeneratorInterface::ABSOLUTE_URL
        )->shouldBeCalled();

        $urls->addUrl(Argument::cetera())->shouldBeCalled();

        $this->populate($event);
    }
}
