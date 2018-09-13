<?php

namespace spec\AppBundle\Updater;

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\GamePlayImage;
use AppBundle\Updater\ImageCountByGamePlayUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;

class ImageCountByGamePlayUpdaterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ImageCountByGamePlayUpdater::class);
    }

    function it_updates_game_play_with_image_count(GamePlay $gamePlay, GamePlayImage $image)
    {
        $gamePlay->getImages()->willReturn(new ArrayCollection([$image->getWrappedObject()]));

        $gamePlay->setImageCount(1)->shouldBeCalled();

        $this->update($gamePlay);
    }
}
