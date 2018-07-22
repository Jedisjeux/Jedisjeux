<?php

namespace spec\AppBundle\Document;

use AppBundle\Document\AppDocument;
use AppBundle\Document\ArticleDocument;
use AppBundle\Document\ImageDocument;
use AppBundle\Document\ProductDocument;
use AppBundle\Document\TopicDocument;
use PhpSpec\ObjectBehavior;

class AppDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AppDocument::class);
    }

    function its_type_is_mutable(): void
    {
        $this->setType(AppDocument::TYPE_TOPIC);
        $this->getType()->shouldReturn(AppDocument::TYPE_TOPIC);
    }

    function its_name_is_mutable(): void
    {
        $this->setName('Luke Skywalker');
        $this->getName()->shouldReturn('Luke Skywalker');
    }

    function its_creation_date_is_mutable(): void
    {
        $createdAt = new \DateTime();
        $this->setCreatedAt($createdAt);
        $this->getCreatedAt()->shouldReturn($createdAt);
    }

    function its_image_is_mutable(ImageDocument $image): void
    {
        $this->setImage($image);
        $this->getImage()->shouldReturn($image);
    }

    function its_topic_is_mutable(TopicDocument $topic): void
    {
        $this->setTopic($topic);
        $this->getTopic()->shouldReturn($topic);
    }

    function its_product_is_mutable(ProductDocument $product): void
    {
        $this->setProduct($product);
        $this->getProduct()->shouldReturn($product);
    }

    function its_article_is_mutable(ArticleDocument $article): void
    {
        $this->setArticle($article);
        $this->getArticle()->shouldReturn($article);
    }
}
