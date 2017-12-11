<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\FestivalList;
use AppBundle\Entity\FestivalListImage;
use AppBundle\Entity\FestivalListItem;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class FestivalListSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FestivalList::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_sets_code()
    {
        $this->setCode('staff_list');

        $this->getCode()->shouldReturn('staff_list');
    }

    function it_sets_name()
    {
        $this->setName('Awesome name');

        $this->getName()->shouldReturn('Awesome name');
    }

    function it_sets_slug()
    {
        $this->setSlug('awesome-name');

        $this->getSlug()->shouldReturn('awesome-name');
    }

    function it_sets_description()
    {
        $this->setName('What an awesome description');

        $this->getName()->shouldReturn('What an awesome description');
    }

    function it_sets_start_at(\DateTime $startAt)
    {
        $this->setStartAt($startAt);

        $this->getStartAt()->shouldReturn($startAt);
    }

    function it_sets_end_at(\DateTime $endAt)
    {
        $this->setEndAt($endAt);

        $this->getEndAt()->shouldReturn($endAt);
    }

    function it_has_no_image_by_default()
    {
        $this->getImage()->shouldReturn(null);
    }

    function its_image_is_mutable(FestivalListImage $image)
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }

    function its_products_is_collection()
    {
        $this->getItems()->shouldHaveType(ArrayCollection::class);
    }

    function it_can_add_items(FestivalListItem $item)
    {
        $this->addItem($item);
        $this->hasItem($item)->shouldReturn(true);
    }

    function it_can_remove_items(FestivalListItem $item)
    {
        $this->addItem($item);
        $this->removeItem($item);
        $this->hasItem($item)->shouldReturn(false);
    }
}
