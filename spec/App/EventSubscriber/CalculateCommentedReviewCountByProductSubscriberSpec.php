<?php

namespace spec\App\EventSubscriber;

use App\Entity\Product;
use App\Updater\CommentedReviewCountByProductUpdater;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use PhpSpec\ObjectBehavior;

class CalculateCommentedReviewCountByProductSubscriberSpec extends ObjectBehavior
{
    function let(CommentedReviewCountByProductUpdater $updater)
    {
        $this->beConstructedWith($updater);
    }

    function it_subscribes_to_doctrine_events(): void
    {
        $this->getSubscribedEvents()->shouldReturn([
            Events::prePersist,
            Events::preUpdate,
        ]);
    }

    function it_updates_commented_review_count_on_product_create_event(
        LifecycleEventArgs $args,
        CommentedReviewCountByProductUpdater $updater,
        Product $product
    ): void {
        $args->getSubject()->willReturn($product);

        $updater->update($product)->shouldBeCalled();

        $this->prePersist($args);
    }

    function it_updates_commented_review_count_on_product_update_event(
        LifecycleEventArgs $args,
        CommentedReviewCountByProductUpdater $updater,
        Product $product
    ): void {
        $args->getSubject()->willReturn($product);

        $updater->update($product)->shouldBeCalled();

        $this->preUpdate($args);
    }
}
