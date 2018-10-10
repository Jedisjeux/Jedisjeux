<?php

namespace spec\App\Entity;

use App\Entity\GamePlay;
use App\Entity\GamePlayImage;
use App\Entity\Player;
use App\Entity\Topic;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class GamePlaySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GamePlay::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_code_is_mutable()
    {
        $this->setCode("GAME_PLAY1");
        $this->getCode()->shouldReturn("GAME_PLAY1");
    }

    function it_has_no_duration_by_default()
    {
        $this->getDuration()->shouldReturn(null);
    }

    function its_duration_is_mutable()
    {
        $this->setDuration(30);
        $this->getDuration()->shouldReturn(30);
    }

    function it_has_no_player_count_by_default()
    {
        $this->getPlayerCount()->shouldReturn(null);
    }

    function its_player_count_is_mutable()
    {
        $this->setPlayerCount(4);
        $this->getPlayerCount()->shouldReturn(4);
    }

    function its_played_at_today_by_default()
    {
        $today = new \DateTime('today');
        $this->getPlayedAt()->shouldBeAnInstanceOf(\DateTime::class);
        $this->getPlayedAt()->format('Y-m-d')->shouldBeEqualTo($today->format('Y-m-d'));
    }

    function it_has_no_product_by_default()
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product)
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function it_has_no_author_by_default()
    {
        $this->getAuthor()->shouldReturn(null);
    }

    function its_author_is_mutable(CustomerInterface $author)
    {
        $this->setAuthor($author);
        $this->getAuthor()->shouldReturn($author);
    }

    function it_has_no_topic_by_default()
    {
        $this->getTopic()->shouldReturn(null);
    }

    function its_topic_is_mutable(Topic $topic)
    {
        $this->setTopic($topic);
        $this->getTopic()->shouldReturn($topic);
    }

    function it_initializes_images_collection_by_default()
    {
        $this->getImages()->shouldHaveType(Collection::class);
    }

    function it_adds_image(GamePlayImage $image)
    {
        $this->addImage($image);
        $this->hasImage($image)->shouldReturn(true);
    }

    function it_removes_image(GamePlayImage $image)
    {
        $this->addImage($image);
        $this->removeImage($image);
        $this->hasImage($image)->shouldReturn(false);
    }

    function its_image_count_is_mutable(): void
    {
        $this->setImageCount(2);
        $this->getImageCount()->shouldReturn(2);
    }

    function it_initializes_players_collection_by_default()
    {
        $this->getPlayers()->shouldHaveType(Collection::class);
    }

    function it_adds_player(Player $player)
    {
        $this->addPlayer($player);
        $this->hasPlayer($player)->shouldReturn(true);
    }

    function it_removes_player(Player $player)
    {
        $this->addPlayer($player);
        $this->removePlayer($player);
        $this->hasPlayer($player)->shouldReturn(false);
    }
}