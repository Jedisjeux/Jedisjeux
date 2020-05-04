<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\ProductInterface;
use App\Entity\Taxon;
use App\Repository\ProductRepository;
use App\Repository\TaxonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateProductTaxonSitemapSubscriber implements EventSubscriberInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var TaxonRepository */
    private $taxonRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        TaxonRepository $taxonRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->taxonRepository = $taxonRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populate',
        ];
    }

    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerProductTaxonssUrls($event->getUrlContainer());
    }

    private function registerProductTaxonssUrls(UrlContainerInterface $urls): void
    {
        $productTaxonsCodes = [
            Taxon::CODE_MECHANISM,
            Taxon::CODE_THEME,
            Taxon::CODE_TARGET_AUDIENCE,
        ];

        $rootTaxons = $this->taxonRepository->findRootNodes();
        $productTaxons = array_filter($rootTaxons, function(Taxon $rootTaxon) use ($productTaxonsCodes) {
            return in_array($rootTaxon->getCode(), $productTaxonsCodes);
        });

        foreach ($productTaxons as $rootTaxon) {
            foreach ($this->taxonRepository->findChildrenAsTree($rootTaxon, false) as $taxon) {
                $urls->addUrl(
                    new UrlConcrete(
                        $this->urlGenerator->generate(
                            'sylius_frontend_product_index_by_taxon',
                            ['slug' => $taxon->getSlug()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ),
                    'product_taxon'
                );
            }
        }
    }
}
