<?php

namespace spec\App\EventSubscriber;

use App\Entity\GamePlay;
use App\EventSubscriber\CalculateImageCountByGamePlaySubscriber;
use App\Updater\ImageCountByGamePlayUpdater;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use PhpSpec\ObjectBehavior;

class CalculateImageCountByGamePlaySubscriberSpec extends ObjectBehavior
{
    function let(ImageCountByGamePlayUpdater $updater)
    {
        $this->beConstructedWith($updater);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CalculateImageCountByGamePlaySubscriber::class);
    }

    function it_subscribes_to_doctrine_events(): void
    {
        $this->getSubscribedEvents()->shouldReturn([
            Events::prePersist,
            Events::preUpdate,
        ]);
    }

    function it_updates_image_count_on_pre_persist_event(
        LifecycleEventArgs $args,
        GamePlay $gamePlay,
        ImageCountByGamePlayUpdater $updater
    ) {
        $args->getObject()->willReturn($gamePlay);

        $updater->update($gamePlay)->shouldBeCalled();

        $this->prePersist($args);
    }

    function it_updates_image_count_on_pre_update_event(
        LifecycleEventArgs $args,
        GamePlay $gamePlay,
        ImageCountByGamePlayUpdater $updater
    ) {
        $args->getObject()->willReturn($gamePlay);

        $updater->update($gamePlay)->shouldBeCalled();

        $this->preUpdate($args);
    }

    function it_does_nothing_when_args_object_is_not_game_play_type(
        LifecycleEventArgs $args,
        \stdClass $gamePlay,
        ImageCountByGamePlayUpdater $updater
    ): void {
        $args->getObject()->willReturn($gamePlay);

        $updater->update($gamePlay)->shouldNotBeCalled();

        $this->updateImageCount($args);
    }
}
