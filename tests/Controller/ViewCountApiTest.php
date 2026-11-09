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

namespace App\Tests\Controller;

use ApiTestCase\JsonApiTestCase;
use App\Entity\Product;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

final class ViewCountApiTest extends JsonApiTestCase
{
    #[Before]
    public function clearCountedVisitors(): void
    {
        static::getContainer()->get('view_count.cache')->clear();
    }

    #[Test]
    public function it_counts_a_view_of_a_product(): void
    {
        $product = $this->loadProduct();

        $this->client->request('POST', $this->getViewsUrl($product));

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_NO_CONTENT);
        self::assertSame(1, $this->getViewCount($product));
    }

    #[Test]
    public function it_counts_a_view_only_once_for_the_same_visitor(): void
    {
        $product = $this->loadProduct();

        $this->client->request('POST', $this->getViewsUrl($product));
        $this->client->request('POST', $this->getViewsUrl($product));

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_NO_CONTENT);
        self::assertSame(1, $this->getViewCount($product));
    }

    #[Test]
    public function it_does_not_count_a_view_from_a_bot(): void
    {
        $product = $this->loadProduct();

        $this->client->request('POST', $this->getViewsUrl($product), [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        ]);

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_NO_CONTENT);
        self::assertSame(0, $this->getViewCount($product));
    }

    #[Test]
    public function it_does_not_allow_counting_views_with_a_get_request(): void
    {
        $product = $this->loadProduct();

        $this->client->request('GET', $this->getViewsUrl($product));

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_METHOD_NOT_ALLOWED);
        self::assertSame(0, $this->getViewCount($product));
    }

    #[Test]
    public function it_does_not_count_views_of_an_unknown_resource(): void
    {
        $this->client->request('POST', '/api/people/1/views');

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    private function loadProduct(): Product
    {
        $products = $this->loadFixturesFromFile('resources/products.yml');

        return $products['product1'];
    }

    private function getViewsUrl(Product $product): string
    {
        return sprintf('/api/products/%d/views', $product->getId());
    }

    private function getViewCount(Product $product): int
    {
        return (int) $this->getEntityManager()->getConnection()->fetchOne(
            'SELECT view_count FROM sylius_product WHERE id = :id',
            ['id' => $product->getId()],
        );
    }
}
