<?php

namespace spec\App\Entity;

use App\Entity\BggProduct;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BggProductSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BggProduct::class);
    }

    function its_name_is_mutable(): void
    {
        $this->setName('Puerto Rico');
        $this->getName()->shouldReturn('Puerto Rico');
    }

    function its_image_path_is_mutable(): void
    {
        $this->setImagePath('/tmp/image.jpg');
        $this->getImagePath()->shouldReturn('/tmp/image.jpg');
    }

    function its_released_at_year_is_mutable(): void
    {
        $this->setReleasedAtYear('2005');
        $this->getReleasedAtYear()->shouldReturn('2005');
    }

    function its_min_duration_is_mutable(): void
    {
        $this->setMinDuration('60');
        $this->getMinDuration()->shouldReturn('60');
    }

    function its_max_duration_is_mutable(): void
    {
        $this->setMaxDuration('120');
        $this->getMaxDuration()->shouldReturn('120');
    }

    function its_description_is_mutable(): void
    {
        $this->setDescription('This is an awesome description.');
        $this->getDescription()->shouldReturn('This is an awesome description.');
    }

    function its_age_is_mutable(): void
    {
        $this->setAge('12');
        $this->getAge()->shouldReturn('12');
    }

    function its_min_player_count_is_mutable(): void
    {
        $this->setMinPlayerCount('2');
        $this->getMinPlayerCount()->shouldReturn('2');
    }

    function its_max_player_count_is_mutable(): void
    {
        $this->setMaxPlayerCount('5');
        $this->getMaxPlayerCount()->shouldReturn('5');
    }

    function it_initializes_mechanisms_array_by_default(): void
    {
        $this->getMechanisms()->shouldReturn([]);
    }

    function it_adds_mechanisms(): void
    {
        $this->addMechanism('Variable Phase Order');
        $this->hasMechanism('Variable Phase Order')->shouldReturn(true);
    }

    function it_removes_mechanisms(): void
    {
        $this->addMechanism('Variable Phase Order');
        $this->removeMechanism('Variable Phase Order');
        $this->hasMechanism('Variable Phase Order')->shouldReturn(false);
    }

    function it_initializes_designers_array_by_default(): void
    {
        $this->getDesigners()->shouldReturn([]);
    }

    function it_adds_designers(): void
    {
        $this->addDesigner('Andreas Seyfarth');
        $this->hasDesigner('Andreas Seyfarth')->shouldReturn(true);
    }

    function it_removes_designers(): void
    {
        $this->addDesigner('Andreas Seyfarth');
        $this->removeDesigner('Andreas Seyfarth');
        $this->hasDesigner('Andreas Seyfarth')->shouldReturn(false);
    }

    function it_initializes_artists_array_by_default(): void
    {
        $this->getArtists()->shouldReturn([]);
    }

    function it_adds_artists(): void
    {
        $this->addArtist('Franz Vohwinkel');
        $this->hasArtist('Franz Vohwinkel')->shouldReturn(true);
    }

    function it_removes_artists(): void
    {
        $this->addArtist('Franz Vohwinkel');
        $this->removeArtist('Franz Vohwinkel');
        $this->hasArtist('Franz Vohwinkel')->shouldReturn(false);
    }

    function it_initializes_publishers_array_by_default(): void
    {
        $this->getPublishers()->shouldReturn([]);
    }

    function it_add_publishers(): void
    {
        $this->addPublisher('Alea');
        $this->hasPublisher('Alea')->shouldReturn(true);
    }

    function it_removes_publishers(): void
    {
        $this->addPublisher('Alea');
        $this->removePublisher('Alea');
        $this->hasPublisher('Alea')->shouldReturn(false);
    }
}
