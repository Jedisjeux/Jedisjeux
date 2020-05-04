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
        $queryBuilder = $this->taxonRepository->createQueryBuilder('o');

        foreach ($queryBuilder->getQuery()->iterate() as $row) {
            /** @var TaxonInterface $taxon */
            $taxon = $row[0];

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

            $this->entityManager->clear(get_class($taxon));
        }
    }
}
