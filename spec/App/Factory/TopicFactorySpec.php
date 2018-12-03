<?php

namespace spec\App\Factory;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Topic;
use App\Factory\TopicFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class TopicFactorySpec extends ObjectBehavior
{
    function let(
        CustomerContextInterface $customerContext,
        RepositoryInterface $gamePlayRepository,
        FactoryInterface $postFactory
    ) {
        $this->beConstructedWith(
            Topic::class,
            $customerContext,
            $gamePlayRepository,
            $postFactory
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TopicFactory::class);
    }

    function it_set_author_with_current_customer(CustomerContextInterface $customerContext, CustomerInterface $customer)
    {
        $customerContext->getCustomer()->willReturn($customer);

        $topic = $this->createNew();
        $topic->getAuthor()->shouldReturn($customer);
    }

    function it_can_create_a_topic_for_a_game_play(GamePlay $gamePlay)
    {
        $topic = $this->createForGamePlay($gamePlay);

        $gamePlay->setTopic($topic)->shouldHaveBeenCalled();
    }

    function it_can_create_a_topic_for_an_article(Article $article)
    {
        $topic = $this->createForArticle($article);
        $article->setTopic($topic)->shouldHaveBeenCalled();
    }

    function it_can_create_a_topic_for_a_taxon(TaxonInterface $taxon)
    {
        $topic = $this->createForTaxon($taxon);
        $topic->getMainTaxon()->shouldReturn($taxon);
    }
}
