<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\App\Entity;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TopicSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Topic::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_sets_title()
    {
        $this->setTitle('Subject title');

        $this->getTitle()->shouldReturn('Subject title');
    }

    function it_sets_post_count()
    {
        $this->setPostCount(7);

        $this->getPostCount()->shouldReturn(7);
    }

    function its_posts_is_collection()
    {
        $this->getPosts()->shouldHaveType(ArrayCollection::class);
    }

    function its_followers_is_collection()
    {
        $this->getFollowers()->shouldHaveType(ArrayCollection::class);
    }

    function its_author_is_mutable(CustomerInterface $author)
    {
        $this->setAuthor($author);
        $this->getAuthor()->shouldReturn($author);
    }

    function its_main_post_is_mutable(Post $mainPost)
    {
        $this->setMainPost($mainPost);
        $this->getMainPost()->shouldReturn($mainPost);
    }

    function its_main_taxon_is_mutable(TaxonInterface $mainTaxon)
    {
        $this->setMainTaxon($mainTaxon);
        $this->getMainTaxon()->shouldReturn($mainTaxon);
    }

    function its_article_is_mutable(Article $article)
    {
        $this->setArticle($article);
        $this->getArticle()->shouldReturn($article);
    }

    function its_game_play_is_mutable(GamePlay $gamePlay)
    {
        $this->setGamePlay($gamePlay);
        $this->getGamePlay()->shouldReturn($gamePlay);
    }

    function it_can_remove_posts(Post $post)
    {
        $this->addPost($post);
        $this->removePost($post);
        $this->hasPost($post)->shouldReturn(false);
    }

    function it_can_remove_followers(CustomerInterface $follower)
    {
        $this->addFollower($follower);
        $this->removeFollower($follower);
        $this->hasFollower($follower)->shouldReturn(false);
    }
}
