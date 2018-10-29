<?php

namespace spec\App\Entity;

use App\Entity\ProductVideo;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductVideoSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductVideo::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_title_is_mutable()
    {
        $this->setTitle("SteamRollers");
        $this->getTitle()->shouldReturn("SteamRollers");
    }

    function its_path_is_mutable()
    {
        $this->setPath("https://www.youtube.com/embed/oyefFfCHIGs?rel=0");
        $this->getPath()->shouldReturn("https://www.youtube.com/embed/oyefFfCHIGs?rel=0");
    }

    function its_product_is_mutable(ProductInterface $product)
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function its_creation_at_is_mutable(\DateTime $createdAt): void
    {
        $this->setCreatedAt($createdAt);
        $this->getCreatedAt()->shouldReturn($createdAt);
    }

    function its_updated_at_is_mutable(\DateTime $updatedAt): void
    {
        $this->setUpdatedAt($updatedAt);
        $this->getUpdatedAt()->shouldReturn($updatedAt);
    }
}
