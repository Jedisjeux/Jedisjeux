<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\ProductInterface;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateProductSitemapSubscriber implements EventSubscriberInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var ProductRepository */
    private $productRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->productRepository = $productRepository;
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
        $this->registerProductsUrls($event->getUrlContainer());
    }

    private function registerProductsUrls(UrlContainerInterface $urls): void
    {
        $queryBuilder = $this->productRepository->createQueryBuilder('o');

        foreach ($queryBuilder->getQuery()->iterate() as $row) {
            /** @var ProductInterface $product */
            $product = $row[0];

            $urls->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'sylius_frontend_product_show',
                        ['slug' => $product->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    $product->getUpdatedAt()
                ),
                'product'
            );

            $this->entityManager->clear(get_class($product));
        }
    }
}
