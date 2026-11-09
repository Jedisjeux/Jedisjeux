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
use App\Entity\Person;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\ProductVariantImage;
use App\Factory\BggProductFactory;
use App\Factory\ProductFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductFactoryTest extends TestCase
{
    public function testItCreatesNewProductFromBgg(): void
    {
        $variant = $this->createStub(ProductVariant::class);
        $product = $this->createMock(Product::class);
        $productVariantImage = $this->createMock(ProductVariantImage::class);
        $bggProduct = $this->createStub(BggProduct::class);

        $imagePath = tempnam(sys_get_temp_dir(), 'jedisjeux');
        $this->assertIsString($imagePath);

        try {
            $variantFactory = $this->createStub(FactoryInterface::class);
            $variantFactory->method('createNew')->willReturn($variant);

            $factory = $this->createStub(FactoryInterface::class);
            $factory->method('createNew')->willReturn($product);

            $productVariantImageFactory = $this->createStub(FactoryInterface::class);
            $productVariantImageFactory->method('createNew')->willReturn($productVariantImage);

            $slugGenerator = $this->createStub(SlugGeneratorInterface::class);
            $slugGenerator->method('generate')->with('Puerto Rico')->willReturn('puerto-rico');

            $personRepository = $this->createStub(RepositoryInterface::class);
            $personRepository->method('findOneBy')->with($this->isArray())->willReturn(null);

            $bggProductFactory = $this->createStub(BggProductFactory::class);
            $bggProductFactory->method('createByPath')
                ->with('https://boardgamegeek.com/boardgame/3076/puerto-rico')
                ->willReturn($bggProduct);

            $product->method('getFirstVariant')->willReturn($variant);
            $product->method('getName')->willReturn('Puerto Rico');
            $productVariantImage->method('getAbsolutePath')->willReturn($imagePath);

            $bggProduct->method('getName')->willReturn('Puerto Rico');
            $bggProduct->method('getDescription')->willReturn('This is an awesome description.');
            $bggProduct->method('getReleasedAtYear')->willReturn('2005');
            $bggProduct->method('getAge')->willReturn('12');
            $bggProduct->method('getMinDuration')->willReturn('60');
            $bggProduct->method('getMaxDuration')->willReturn('120');
            $bggProduct->method('getMinPlayerCount')->willReturn('2');
            $bggProduct->method('getMaxPlayerCount')->willReturn('5');
            $bggProduct->method('getMechanisms')->willReturn([]);
            $bggProduct->method('getDesigners')->willReturn([]);
            $bggProduct->method('getArtists')->willReturn([]);
            $bggProduct->method('getPublishers')->willReturn([]);
            $bggProduct->method('getImagePath')->willReturn(__FILE__);

            $product->expects($this->once())->method('setName')->with('Puerto Rico');
            $product->expects($this->once())->method('addVariant')->with($variant);
            $product->expects($this->once())->method('setSlug')->with('puerto-rico');
            $product->expects($this->once())->method('setDescription')->with('This is an awesome description.');
            $product->expects($this->once())->method('setMinAge')->with(12);
            $product->expects($this->once())->method('setMinDuration')->with(60);
            $product->expects($this->once())->method('setMaxDuration')->with(120);
            $product->expects($this->once())->method('setMinPlayerCount')->with(2);
            $product->expects($this->once())->method('setMaxPlayerCount')->with(5);

            $productVariantImage->expects($this->once())->method('setPath')->with($this->isString());
            $productVariantImage->expects($this->once())->method('setMain')->with(true);

            $productFactory = new ProductFactory(
                $factory,
                $variantFactory,
                $productVariantImageFactory,
                $bggProductFactory,
                $slugGenerator,
                $personRepository,
                $this->createStub(FactoryInterface::class),
            );

            $this->assertInstanceOf(
                Product::class,
                $productFactory->createFromBgg('https://boardgamegeek.com/boardgame/3076/puerto-rico'),
            );
        } finally {
            unlink($imagePath);
        }
    }

    public function testItCreatesMissingPersonsFromBgg(): void
    {
        $variant = new ProductVariant();
        $product = $this->createStub(Product::class);
        $product->method('getFirstVariant')->willReturn($variant);
        $product->method('getName')->willReturn('Puerto Rico');

        $factory = $this->createStub(FactoryInterface::class);
        $factory->method('createNew')->willReturn($product);

        $variantFactory = $this->createStub(FactoryInterface::class);
        $variantFactory->method('createNew')->willReturn($variant);

        $bggProduct = $this->createStub(BggProduct::class);
        $bggProduct->method('getName')->willReturn('Puerto Rico');
        $bggProduct->method('getDesigners')->willReturn(['Andreas Seyfarth', '(Uncredited)']);
        $bggProduct->method('getArtists')->willReturn(['Franz Vohwinkel', 'Jeff Smith (I)']);
        $bggProduct->method('getPublishers')->willReturn(['Rio Grande Games']);

        $bggProductFactory = $this->createStub(BggProductFactory::class);
        $bggProductFactory->method('createFromPayload')->willReturn($bggProduct);

        $existingPerson = new Person();
        $existingPerson->setFirstName('Franz');
        $existingPerson->setLastName('Vohwinkel');

        $personRepository = $this->createMock(RepositoryInterface::class);
        $personRepository->method('findOneBy')->willReturnCallback(
            fn (array $criteria) => 'franz-vohwinkel' === $criteria['slug'] ? $existingPerson : null,
        );
        $personRepository->expects($this->exactly(3))->method('add');

        $personFactory = $this->createStub(FactoryInterface::class);
        $personFactory->method('createNew')->willReturnCallback(fn () => new Person());

        $productFactory = new ProductFactory(
            $factory,
            $variantFactory,
            $this->createStub(FactoryInterface::class),
            $bggProductFactory,
            $this->createStub(SlugGeneratorInterface::class),
            $personRepository,
            $personFactory,
        );

        $productFactory->createFromBggPayload('payload');

        $this->assertSame(['Andreas Seyfarth'], $this->getFullNames($variant->getDesigners()));
        $this->assertSame(['Franz Vohwinkel', 'Jeff Smith'], $this->getFullNames($variant->getArtists()));
        $this->assertSame(['Rio Grande Games'], $this->getFullNames($variant->getPublishers()));
        $this->assertSame($existingPerson, $variant->getArtists()->first());

        /** @var Person $designer */
        $designer = $variant->getDesigners()->first();
        $this->assertSame('Andreas', $designer->getFirstName());
        $this->assertSame('Seyfarth', $designer->getLastName());

        /** @var Person $publisher */
        $publisher = $variant->getPublishers()->first();
        $this->assertNull($publisher->getFirstName());
        $this->assertSame('Rio Grande Games', $publisher->getLastName());
    }

    /**
     * @param iterable<Person> $persons
     *
     * @return list<string>
     */
    private function getFullNames(iterable $persons): array
    {
        $fullNames = [];

        foreach ($persons as $person) {
            $fullNames[] = $person->getFullName();
        }

        return $fullNames;
    }
}
