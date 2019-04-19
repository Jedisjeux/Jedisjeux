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

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    public function it_initializes_a_code_by_default(): void
    {
        $this->getCode()->shouldNotBeNull();
    }

    function its_code_is_mutable()
    {
        $this->setCode('XYZ');

        $this->getCode()->shouldReturn('XYZ');
    }

    function it_has_no_title_by_default()
    {
        $this->getTitle()->shouldReturn(null);
    }

    function its_title_is_mutable()
    {
        $this->setTitle('Subject title');

        $this->getTitle()->shouldReturn('Subject title');
    }

    function its_post_count_is_equal_to_zero_by_default()
    {
        $this->getPostCount()->shouldReturn(0);
    }

    function it_sets_post_count()
    {
        $this->setPostCount(7);

        $this->getPostCount()->shouldReturn(7);
    }

    function it_has_zero_view_count_by_default(): void
    {
        $this->getViewCount()->shouldReturn(0);
    }

    function its_view_count_is_mutable(): void
    {
        $this->setViewCount(42);
        $this->getViewCount()->shouldReturn(42);
    }

    function it_has_no_last_post_creation_date_by_default(): void
    {
        $this->getLastPostCreatedAt()->shouldReturn(null);
    }

    function its_last_post_creation_date_by_default(): void
    {
        $lastPostCreatedAt = new \DateTime();
        $this->setLastPostCreatedAt($lastPostCreatedAt);
        $this->getLastPostCreatedAt()->shouldReturn($lastPostCreatedAt);
    }

    function it_ha_no_first_post_by_default()
    {
        $this->getFirstPost()->shouldReturn(null);
    }

    function it_can_get_first_post(Post $firstPost, Post $lastPost)
    {
        $this->addPost($firstPost);
        $this->addPost($lastPost);

        $this->getFirstPost()->shouldReturn($firstPost);
    }

    function it_ha_no_last_post_by_default()
    {
        $this->getLastPost()->shouldReturn(null);
    }

    function it_can_get_last_post(Post $firstPost, Post $lastPost)
    {
        $this->addPost($firstPost);
        $this->addPost($lastPost);

        $this->getLastPost()->shouldReturn($lastPost);
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

    function it_initializes_posts_collection_by_default()
    {
        $this->getPosts()->shouldHaveType(ArrayCollection::class);
    }

    function it_adds_posts(Post $post)
    {
        $post->setTopic($this)->shouldBeCalled();

        $this->addPost($post);
        $this->hasPost($post)->shouldReturn(true);
    }

    function it_remove_posts(Post $post)
    {
        $this->addPost($post);

        $post->setTopic(null)->shouldBeCalled();

        $this->removePost($post);
        $this->hasPost($post)->shouldReturn(false);
    }

    function it_initializes_followers_collection_by_default()
    {
        $this->getFollowers()->shouldHaveType(ArrayCollection::class);
    }

    function it_add_followers(CustomerInterface $follower)
    {
        $this->addFollower($follower);
        $this->hasFollower($follower)->shouldReturn(true);
    }

    function it_remove_followers(CustomerInterface $follower)
    {
        $this->addFollower($follower);
        $this->removeFollower($follower);
        $this->hasFollower($follower)->shouldReturn(false);
    }

    function it_has_no_last_page_number_by_default()
    {
        $this->getLastPageNumber()->shouldReturn(null);
    }

    function it_has_no_last_page_number_when_topic_has_ten_posts()
    {
        $this->setPostCount(10);

        $this->getLastPageNumber()->shouldReturn(null);
    }

    function its_last_page_number_can_equals_to_one_when_topic_has_ten_posts()
    {
        $this->setPostCount(10);

        $this->getLastPageNumber(false)->shouldReturn(1);
    }

    function its_last_page_number_equals_to_two_when_topic_has_eleven_posts()
    {
        $this->setPostCount(11);

        $this->getLastPageNumber()->shouldReturn(2);
    }

    function its_string_conversion_returns_title()
    {
        $this->setTitle('u-topic');
        $this::__toString()->shouldReturn('u-topic');
    }
}
