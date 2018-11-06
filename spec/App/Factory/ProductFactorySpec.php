<?php

namespace spec\App\Factory;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\ProductVariantImage;
use App\Factory\ProductFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProductFactorySpec extends ObjectBehavior
{
    function let(
        FactoryInterface $factory,
        FactoryInterface $variantFactory,
        FactoryInterface $productVariantImageFactory,
        SlugGeneratorInterface $slugGenerator,
        RepositoryInterface $personRepository
    ) {
        $this->beConstructedWith($factory, $variantFactory, $productVariantImageFactory, $slugGenerator, $personRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductFactory::class);
    }

    function it_creates_new_product_from_bgg(
        FactoryInterface $factory,
        FactoryInterface $variantFactory,
        FactoryInterface $productVariantImageFactory,
        Product $product,
        ProductVariant $variant,
        SlugGeneratorInterface $slugGenerator,
        RepositoryInterface $personRepository,
        ProductVariantImage $productVariantImage
    ): void {
        $variantFactory->createNew()->willReturn($variant);
        $factory->createNew()->willReturn($product);
        $product->getFirstVariant()->willReturn($variant);
        $product->getName()->willReturn('Puerto Rico');
        $slugGenerator->generate('Puerto Rico')->willReturn('puerto-rico');
        $personRepository->findOneBy(Argument::type('array'))->willReturn(null);
        $productVariantImageFactory->createNew()->willReturn($productVariantImage);
        $productVariantImage->getAbsolutePath()->willReturn('/tmp/image.png');

        $product->setName('Puerto Rico')->shouldBeCalled();
        $product->addVariant($variant)->shouldBeCalled();
        $product->setSlug('puerto-rico')->shouldBeCalled();
        $product->setDescription(Argument::type('string'))->shouldBeCalled();
        $product->setMinAge(Argument::type('int'))->shouldBeCalled();
        $product->setMinDuration(Argument::type('int'))->shouldBeCalled();
        $product->setMaxDuration(Argument::type('int'))->shouldBeCalled();
        $product->setMinPlayerCount(Argument::type('int'))->shouldBeCalled();
        $product->setMaxPlayerCount(Argument::type('int'))->shouldBeCalled();

        $productVariantImage->setPath(Argument::type('string'))->shouldBeCalled();
        $productVariantImage->setMain(true)->shouldBeCalled();

        $this->createFromBgg('https://boardgamegeek.com/boardgame/3076/puerto-rico')->shouldReturn($product);
    }
}
