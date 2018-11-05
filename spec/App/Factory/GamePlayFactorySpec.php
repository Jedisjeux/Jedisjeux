<?php

namespace spec\App\Factory;

use App\Context\CustomerContext;
use App\Entity\GamePlay;
use App\Factory\GamePlayFactory;
use App\Repository\ProductRepository;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

class GamePlayFactorySpec extends ObjectBehavior
{
    function let(ProductRepository $productRepository, CustomerContext $customerContext)
    {
        $this->beConstructedWith(GamePlay::class, $productRepository, $customerContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GamePlayFactory::class);
    }

    function it_set_author_with_current_customer(
        CustomerContext $customerContext,
        CustomerInterface $customer
    ) {
        $customerContext->getCustomer()->willReturn($customer);

        $gamePlay = $this->createNew();
        $gamePlay->getAuthor()->shouldReturn($customer);
    }

    function it_creates_for_product(
        ProductRepository $productRepository,
        ProductInterface $product
    ) {
        $productRepository->findOneBySlug('en_US', 'puerto-rico')->willReturn($product);

        $gamePlay = $this->createForProduct('en_US', 'puerto-rico');
        $gamePlay->getProduct()->shouldReturn($product);
    }
}
