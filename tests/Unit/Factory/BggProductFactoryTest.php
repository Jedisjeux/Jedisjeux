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

namespace App\Tests\Unit\Factory;

use App\Entity\BggProduct;
use App\Factory\BggProductFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class BggProductFactoryTest extends TestCase
{
    private BggProductFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new BggProductFactory(BggProduct::class, 'https://boardgamegeek.com/xmlapi/boardgame');
    }

    public function testItCanCreateBggProductByPath(): void
    {
        // The BGG XML API now requires an authorization token
        $this->markTestSkipped('Requires access to the BoardGameGeek API');
    }

    public function testItCreatesBggProductFromBookmarkletPayload(): void
    {
        $payload = rtrim(strtr(base64_encode(gzdeflate(json_encode([
            'name' => 'Puerto Rico',
            'releasedAtYear' => 2002,
            'minDuration' => '90',
            'maxDuration' => '150',
            'age' => 12,
            'minPlayerCount' => 3,
            'maxPlayerCount' => 5,
            'description' => 'In &quot;Puerto Rico&quot;,<br/>players&#10;assume roles.',
            'imagePath' => 'https://cf.geekdo-images.com/abc__original/img/def=/0x0/filters:format(jpeg)/pic158548.jpg',
            'mechanisms' => ['Variable Phase Order'],
            'categories' => ['Economic', 'Farming'],
            'designers' => ['Andreas Seyfarth'],
            'artists' => ['Harald Lieske', 'Franz Vohwinkel'],
            'publishers' => ['alea'],
        ]))), '+/', '-_'), '=');

        $bggProduct = $this->factory->createFromPayload($payload);

        $this->assertSame('Puerto Rico', $bggProduct->getName());
        $this->assertSame('2002', $bggProduct->getReleasedAtYear());
        $this->assertSame('90', $bggProduct->getMinDuration());
        $this->assertSame('150', $bggProduct->getMaxDuration());
        $this->assertSame('12', $bggProduct->getAge());
        $this->assertSame('3', $bggProduct->getMinPlayerCount());
        $this->assertSame('5', $bggProduct->getMaxPlayerCount());
        $this->assertSame("In \"Puerto Rico\",\nplayers\nassume roles.", $bggProduct->getDescription());
        $this->assertSame('https://cf.geekdo-images.com/abc__original/img/def=/0x0/filters:format(jpeg)/pic158548.jpg', $bggProduct->getImagePath());
        $this->assertSame(['Variable Phase Order'], $bggProduct->getMechanisms());
        $this->assertSame(['Economic', 'Farming'], $bggProduct->getCategories());
        $this->assertSame(['Andreas Seyfarth'], $bggProduct->getDesigners());
        $this->assertSame(['Harald Lieske', 'Franz Vohwinkel'], $bggProduct->getArtists());
        $this->assertSame(['alea'], $bggProduct->getPublishers());
    }

    public function testItIgnoresImagesNotHostedByBgg(): void
    {
        $payload = base64_encode(gzdeflate(json_encode([
            'name' => 'Puerto Rico',
            'imagePath' => 'http://localhost/secret.jpg',
        ])));

        $this->assertNull($this->factory->createFromPayload($payload)->getImagePath());
    }

    public function testItThrowsAnExceptionWhenPayloadIsInvalid(): void
    {
        $this->expectException(BadRequestHttpException::class);

        $this->factory->createFromPayload('not a payload');
    }
}
