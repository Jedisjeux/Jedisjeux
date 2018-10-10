<?php

namespace spec\App\Entity;

use App\Entity\GamePlay;
use App\Entity\Player;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class PlayerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Player::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_no_score_by_default()
    {
        $this->getScore()->shouldReturn(null);
    }

    function its_score_is_mutable()
    {
        $this->setScore(666.0);
        $this->getScore()->shouldReturn(666.0);
    }

    function it_has_no_name_by_default()
    {
        $this->getName()->shouldReturn(null);
    }

    function its_name_is_mutable()
    {
        $this->setName("John Snow");
        $this->getName()->shouldReturn("John Snow");
    }

    function it_has_no_game_play_by_default()
    {
        $this->getGamePlay()->shouldReturn(null);
    }

    function its_game_play_is_mutable(GamePlay $gamePlay)
    {
        $this->setGamePlay($gamePlay);
        $this->getGamePlay()->shouldReturn($gamePlay);
    }

    function it_has_no_customer_by_default()
    {
        $this->getCustomer()->shouldReturn(null);
    }

    function its_customer_is_mutable(CustomerInterface $customer)
    {
        $this->setCustomer($customer);
        $this->getCustomer()->shouldReturn($customer);
    }
}
