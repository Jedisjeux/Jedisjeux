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

use App\Entity\Article;
use App\Entity\ProductInterface;
use App\Repository\ArticleRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GenerateArticleSitemapSubscriber implements EventSubscriberInterface
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var ArticleRepository */
    private $articleRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->articleRepository = $articleRepository;
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
        $this->registerArticlesUrls($event->getUrlContainer());
    }

    private function registerArticlesUrls(UrlContainerInterface $urls): void
    {
        $queryBuilder = $this->articleRepository->createQueryBuilder('o');

        foreach ($queryBuilder->getQuery()->iterate() as $row) {
            /** @var Article $article */
            $article = $row[0];

            $urls->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'app_frontend_article_show',
                        ['slug' => $article->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    $article->getUpdatedAt()
                ),
                'article'
            );

            $this->entityManager->clear(get_class($article));
        }
    }
}
