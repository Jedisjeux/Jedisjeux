<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\Article;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Topic;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class NotificationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Notification::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_is_not_read_by_default()
    {
        $this->isRead()->shouldReturn(false);
    }

    function it_can_be_read()
    {
        $this->setRead(true);
        $this->isRead()->shouldReturn(true);
    }

    function it_has_no_message_by_default()
    {
        $this->getMessage()->shouldReturn(null);
    }

    function its_message_is_mutable()
    {
        $this->setMessage("You should not pass");
        $this->getMessage()->shouldReturn("You should not pass");
    }

    function it_has_no_target_by_default()
    {
        $this->getTarget()->shouldReturn(null);
    }

    function its_target_is_mutable()
    {
        $this->setTarget("http://example.com");
        $this->getTarget()->shouldReturn("http://example.com");
    }

    function it_has_no_recipient_by_default()
    {
        $this->getRecipient()->shouldReturn(null);
    }

    function its_recipient_is_mutable(CustomerInterface $recipient)
    {
        $this->setRecipient($recipient);
        $this->getRecipient()->shouldReturn($recipient);
    }

    function it_initializes_authors_collection_by_default()
    {
        $this->getAuthors()->shouldHaveType(Collection::class);
    }

    function it_adds_author(CustomerInterface $author)
    {
        $this->addAuthor($author);
        $this->hasAuthor($author)->shouldReturn(true);
    }

    function it_removes_author(CustomerInterface $author)
    {
        $this->addAuthor($author);
        $this->removeAuthor($author);
        $this->hasAuthor($author)->shouldReturn(false);
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

    function it_has_no_product_by_default()
    {
        $this->getProduct()->shouldReturn(null);
    }

    function its_product_is_mutable(ProductInterface $product)
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function it_has_no_article_by_default()
    {
        $this->getArticle()->shouldReturn(null);
    }

    function its_article_is_mutable(Article $article)
    {
        $this->setArticle($article);
        $this->getArticle()->shouldReturn($article);
    }
}
