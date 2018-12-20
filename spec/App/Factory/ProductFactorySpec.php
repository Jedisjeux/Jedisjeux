<?php

namespace spec\App\Factory;

use App\Entity\BggProduct;
use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Entity\ProductImage;
use App\Factory\BggProductFactory;
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
        BggProductFactory $bggProductFactory,
        SlugGeneratorInterface $slugGenerator,
        RepositoryInterface $personRepository
    ) {
        $this->beConstructedWith(
            $factory,
            $variantFactory,
            $productVariantImageFactory,
            $bggProductFactory,
            $slugGenerator,
            $personRepository
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ProductFactory::class);
    }

    function it_creates_new_product_from_bgg(
        FactoryInterface $factory,
        FactoryInterface $variantFactory,
        FactoryInterface $productVariantImageFactory,
        BggProductFactory $bggProductFactory,
        Product $product,
        ProductVariant $variant,
        SlugGeneratorInterface $slugGenerator,
        RepositoryInterface $personRepository,
        ProductImage $productVariantImage,
        BggProduct $bggProduct
    ): void {
        $variantFactory->createNew()->willReturn($variant);
        $factory->createNew()->willReturn($product);
        $product->getFirstVariant()->willReturn($variant);
        $product->getName()->willReturn('Puerto Rico');
        $slugGenerator->generate('Puerto Rico')->willReturn('puerto-rico');
        $personRepository->findOneBy(Argument::type('array'))->willReturn(null);
        $productVariantImageFactory->createNew()->willReturn($productVariantImage);
        $productVariantImage->getAbsolutePath()->willReturn('/tmp/image.png');
        $bggProductFactory->createByPath('https://boardgamegeek.com/boardgame/3076/puerto-rico')->willReturn($bggProduct);

        $bggProduct->getName()->willReturn('Puerto Rico');
        $bggProduct->getDescription()->willReturn('This is an awesome description.');
        $bggProduct->getReleasedAtYear()->willReturn('2005');
        $bggProduct->getAge()->willReturn('12');
        $bggProduct->getMinDuration()->willReturn('60');
        $bggProduct->getMaxDuration()->willReturn('120');
        $bggProduct->getMinPlayerCount()->willReturn('2');
        $bggProduct->getMaxPlayerCount()->willReturn('5');
        $bggProduct->getMechanisms()->willReturn([]);
        $bggProduct->getDesigners()->willReturn([]);
        $bggProduct->getArtists()->willReturn([]);
        $bggProduct->getPublishers()->willReturn([]);
        $bggProduct->getImagePath()->willReturn(__FILE__);

        $product->setName('Puerto Rico')->shouldBeCalled();
        $product->addVariant($variant)->shouldBeCalled();
        $product->setSlug('puerto-rico')->shouldBeCalled();
        $product->setDescription('This is an awesome description.')->shouldBeCalled();
        $product->setMinAge(12)->shouldBeCalled();
        $product->setMinDuration(60)->shouldBeCalled();
        $product->setMaxDuration(120)->shouldBeCalled();
        $product->setMinPlayerCount(2)->shouldBeCalled();
        $product->setMaxPlayerCount(5)->shouldBeCalled();

        $productVariantImage->setPath(Argument::type('string'))->shouldBeCalled();
        $productVariantImage->setMain(true)->shouldBeCalled();

        $this->createFromBgg('https://boardgamegeek.com/boardgame/3076/puerto-rico')->shouldHaveType(Product::class);
    }
}
