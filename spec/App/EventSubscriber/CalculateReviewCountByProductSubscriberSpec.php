<?php

namespace spec\App\EventSubscriber;

use App\Entity\Product;
use App\Entity\ProductInterface;
use App\Entity\ProductReview;
use App\Event\ProductEvents;
use App\Updater\ReviewCountByProductUpdater;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\GenericEvent;

class CalculateReviewCountByProductSubscriberSpec extends ObjectBehavior
{
    function let(ReviewCountByProductUpdater $updater, EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($updater, $entityManager);
    }

    function it_subscribes_to_product_events()
    {
        $this::getSubscribedEvents()->shouldReturn([
            'sylius.product_review.post_create' => 'onProductCreate',
            'sylius.product_review.post_update' => 'onProductUpdate',
        ]);
    }

    function it_updates_review_count_on_product_create_event(
        GenericEvent $event,
        ReviewCountByProductUpdater $updater,
        ProductReview $productReview,
        ProductInterface $product,
        EntityManagerInterface $entityManager
    ): void {
        $event->getSubject()->willReturn($productReview);
        $productReview->getReviewSubject()->willReturn($product);

        $updater->update($product)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->onProductCreate($event);
    }

    function it_updates_review_count_on_product_update_event(
        GenericEvent $event,
        ReviewCountByProductUpdater $updater,
        ProductReview $productReview,
        Product $product,
        EntityManagerInterface $entityManager
    ): void {
        $event->getSubject()->willReturn($productReview);
        $productReview->getReviewSubject()->willReturn($product);

        $updater->update($product)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();

        $this->onProductUpdate($event);
    }
}
