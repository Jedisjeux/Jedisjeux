<?php

namespace spec\App\Factory;

use App\Entity\FestivalList;
use App\Entity\FestivalListItem;
use App\Factory\FestivalListItemFactory;
use PhpSpec\ObjectBehavior;

class FestivalListItemFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(FestivalListItem::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FestivalListItemFactory::class);
    }

    function it_can_creates_a_festival_list_item()
    {
        $this->createNew()->shouldHaveType(FestivalListItem::class);
    }

    function it_can_creates_a_festival_list_item_for_a_list(FestivalList $list)
    {
        $item = $this->createForList($list);

        $item->getList()->shouldReturn($list);
    }
}
