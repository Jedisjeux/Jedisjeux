<?php

namespace spec\App\Factory;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use App\Factory\PostFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Customer\Context\CustomerContextInterface;

class PostFactorySpec extends ObjectBehavior
{
    function let(CustomerContextInterface $customerContext)
    {
        $this->beConstructedWith(Post::class, $customerContext);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PostFactory::class);
    }

    function it_can_create_a_post()
    {
        $this->createNew()->shouldHaveType(Post::class);
    }

    function it_can_create_a_post_for_a_topic(Topic $topic)
    {
        $topic->hasPost(Argument::type(Post::class))->shouldBeCalled();
        $topic->addPost(Argument::type(Post::class))->shouldBeCalled();

        $post = $this->createForTopic($topic);

        $post->getTopic()->shouldReturn($topic);
    }

    function it_can_create_a_post_for_a_game_play(GamePlay $gamePlay)
    {
        $post = $this->createForGamePlay($gamePlay);

        $post->getGamePlay()->shouldReturn($gamePlay);
    }

    function it_can_create_a_post_for_an_article(Article $article)
    {
        $post = $this->createForArticle($article);

        $post->getArticle()->shouldReturn($article);
    }
}
