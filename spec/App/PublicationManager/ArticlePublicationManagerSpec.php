<?php

namespace spec\App\PublicationManager;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArticlePublicationManagerSpec extends ObjectBehavior
{
    function let(ObjectManager $manager)
    {
        $this->beConstructedWith($manager);
    }

    function it_starts_an_article(Article $article, ObjectManager $manager)
    {
        $article->setPublishStartDate(Argument::type(\DateTime::class))->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->start($article);
    }
}
