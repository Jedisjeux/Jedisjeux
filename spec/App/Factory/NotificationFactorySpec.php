<?php

namespace spec\App\Factory;

use App\Entity\Article;
use App\Entity\Notification;
use App\Entity\ProductBox;
use App\Entity\ProductFile;
use App\Factory\NotificationFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

class NotificationFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(Notification::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NotificationFactory::class);
    }

    function it_can_create_a_notification()
    {
        $notification = $this->createNew();
        $notification->shouldHaveType(Notification::class);
    }

    function it_can_create_a_notification_for_a_customer(CustomerInterface $customer)
    {
        $notification = $this->createForCustomer($customer);
        $notification->getRecipient()->shouldReturn($customer);
    }

    function it_can_create_a_notification_for_a_product(ProductInterface $product, CustomerInterface $customer)
    {
        $notification = $this->createForProduct($product, $customer);
        $notification->getProduct()->shouldReturn($product);
    }

    function it_can_create_a_notification_for_an_article(Article $article, CustomerInterface $customer)
    {
        $notification = $this->createForArticle($article, $customer);
        $notification->getArticle()->shouldReturn($article);
    }

    function it_can_create_a_notification_for_a_product_box(ProductBox $productBox, CustomerInterface $customer)
    {
        $notification = $this->createForProductBox($productBox, $customer);
        $notification->getProductBox()->shouldReturn($productBox);
    }

    function it_can_create_a_notification_for_a_product_file(ProductFile $productFile, CustomerInterface $customer)
    {
        $notification = $this->createForProductFile($productFile, $customer);
        $notification->getProductFile()->shouldReturn($productFile);
    }
}
