<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleImage;
use AppBundle\Entity\Topic;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class ArticleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Article::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function its_code_is_mutable()
    {
        $this->setCode("ARTICLE1");
        $this->getCode()->shouldReturn("ARTICLE1");
    }

    function it_has_no_title_by_default()
    {
        $this->getTitle()->shouldReturn(null);
    }

    function its_title_is_mutable()
    {
        $this->setTitle("Awesome title");
        $this->getTitle()->shouldReturn("Awesome title");
    }

    function it_has_no_publish_start_date_by_default()
    {
        $this->getPublishStartDate()->shouldReturn(null);
    }

    function its_publish_start_date_is_mutable(\DateTime $startDate)
    {
        $this->setPublishStartDate($startDate);
        $this->getPublishStartDate()->shouldReturn($startDate);
    }

    function it_has_no_publish_end_date_by_default()
    {
        $this->getPublishEndDate()->shouldReturn(null);
    }

    function its_publish_end_date_is_mutable(\DateTime $endDate)
    {
        $this->setPublishEndDate($endDate);
        $this->getPublishEndDate()->shouldReturn($endDate);
    }

    function its_status_is_new_by_default()
    {
        $this->getStatus()->shouldReturn(Article::STATUS_NEW);
    }

    function its_status_is_mutable()
    {
        $this->setStatus(Article::STATUS_PENDING_PUBLICATION);
        $this->getStatus()->shouldReturn(Article::STATUS_PENDING_PUBLICATION);
    }

    function it_has_no_main_image_by_default()
    {
        $this->getMainImage()->shouldReturn(null);
    }

    function its_main_image_is_mutable(ArticleImage $image)
    {
        $this->setMainImage($image);
        $this->getMainImage()->shouldReturn($image);
    }

    function it_has_no_main_taxon_by_default()
    {
        $this->getMainTaxon()->shouldReturn(null);
    }

    function its_main_taxon_is_mutable(TaxonInterface $taxon)
    {
        $this->setMainTaxon($taxon);
        $this->getMainTaxon()->shouldReturn($taxon);
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
}
