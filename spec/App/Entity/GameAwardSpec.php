<?php

namespace spec\App\Entity;

use App\Entity\GameAwardImage;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;

class GameAwardSpec extends ObjectBehavior
{
    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_has_no_name_by_default()
    {
        $this->getName()->shouldReturn(null);
    }

    function its_name_is_mutable()
    {
        $this->setName('Spiel des Jahres');
        $this->getName()->shouldReturn('Spiel des Jahres');
    }

    function it_has_no_slug_by_default()
    {
        $this->getSlug()->shouldReturn(null);
    }

    function its_slug_is_mutable()
    {
        $this->setSlug('spiel-des-jahres');
        $this->getSlug()->shouldReturn('spiel-des-jahres');
    }

    function it_has_no_description_by_default()
    {
        $this->getDescription()->shouldReturn(null);
    }

    function its_description_is_mutable()
    {
        $this->setDescription('Howard the duck');
        $this->getDescription()->shouldReturn('Howard the duck');
    }

    function it_has_no_image_by_default()
    {
        $this->getImage()->shouldReturn(null);
    }

    function its_image_is_mutable(GameAwardImage $image)
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }
}
