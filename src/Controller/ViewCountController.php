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

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Product;
use App\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * Counts the views of the articles, games and topics, sent by the frontend with a beacon.
 */
final class ViewCountController
{
    private const ENTITY_CLASSES = [
        'articles' => Article::class,
        'products' => Product::class,
        'topics' => Topic::class,
    ];

    private const BOT_USER_AGENT_PATTERN = '/bot|crawl|spider|slurp|archiver|facebookexternalhit|preview|headless|lighthouse|curl|wget|python|scrapy|httpclient/i';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        #[Autowire(service: 'view_count.cache')]
        private readonly CacheInterface $cache,
    ) {
    }

    public function incrementAction(Request $request, string $resource, int $id): Response
    {
        $entityClass = self::ENTITY_CLASSES[$resource] ?? throw new NotFoundHttpException();
        $userAgent = (string) $request->headers->get('User-Agent');

        if ('' === $userAgent || 1 === preg_match(self::BOT_USER_AGENT_PATTERN, $userAgent)) {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        // A visitor reloading the page or coming back shortly after only counts once
        $key = hash('xxh128', implode('|', [$resource, $id, $request->getClientIp(), $userAgent]));
        $isNewView = false;
        $this->cache->get($key, static function () use (&$isNewView): bool {
            $isNewView = true;

            return true;
        });

        if ($isNewView) {
            $this->entityManager->createQueryBuilder()
                ->update($entityClass, 'o')
                ->set('o.viewCount', 'o.viewCount + 1')
                ->where('o.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->execute();
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
