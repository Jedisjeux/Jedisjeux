<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_topic")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Topic implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     *
     * @JMS\Expose
     */
    protected $title;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $author;

    /**
     * @var Post
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Post", inversedBy="parent", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    protected $mainPost;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="topic", cascade={"persist", "remove"})
     */
    protected $posts;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @JMS\Expose
     */
    protected $postCount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $viewCount = 0;

    /**
     * @var Taxon
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     */
    protected $mainTaxon;

    /**
     * @var GamePlay
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\GamePlay", mappedBy="topic")
     */
    protected $gamePlay;

    /**
     * @var Article
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Article", mappedBy="topic")
     */
    protected $article;

    /**
     * @var ArrayCollection|CustomerInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     * @ORM\JoinTable(name="jdj_topic_follower")
     */
    protected $followers;

    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->postCount = 0;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * @param int $postCount
     *
     * @return $this
     */
    public function setPostCount($postCount)
    {
        $this->postCount = $postCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @param int $viewCount
     *
     * @return $this
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * @return CustomerInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param CustomerInterface $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Post
     */
    public function getMainPost()
    {
        return $this->mainPost;
    }

    /**
     * @param Post $mainPost
     *
     * @return $this
     */
    public function setMainPost($mainPost)
    {
        $this->mainPost = $mainPost;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     *
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @param $post
     *
     * @return $this
     */
    public function addPost(Post $post)
    {
        if (!$this->hasPost($post)) {
            $post->setTopic($this);
            $this->posts->add($post);
        }

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return $this
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function hasPost(Post $post) {
        return $this->posts->contains($post);
    }

    /**
     * @return Taxon|TaxonInterface
     */
    public function getMainTaxon()
    {
        return $this->mainTaxon;
    }

    /**
     * @param TaxonInterface $mainTaxon
     *
     * @return $this
     */
    public function setMainTaxon($mainTaxon)
    {
        $this->mainTaxon = $mainTaxon;

        return $this;
    }

    /**
     * @return GamePlay
     */
    public function getGamePlay()
    {
        return $this->gamePlay;
    }

    /**
     * @param GamePlay $gamePlay
     *
     * @return $this
     */
    public function setGamePlay($gamePlay)
    {
        $this->gamePlay = $gamePlay;

        return $this;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     *
     * @return $this
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return ArrayCollection|\Sylius\Component\Customer\Model\CustomerInterface[]
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param CustomerInterface $follower
     *
     * @return $this
     */
    public function addFollower(CustomerInterface $follower)
    {
        if (!$this->hasFollower($follower)) {
            $this->followers->add($follower);
        }

        return $this;
    }

    /**
     * @param CustomerInterface $follower
     *
     * @return $this
     */
    public function removeFollower(CustomerInterface $follower)
    {
        $this->followers->removeElement($follower);

        return $this;
    }

    /**
     * @param CustomerInterface $follower
     *
     * @return bool
     */
    public function hasFollower(CustomerInterface $follower)
    {
        return $this->followers->contains($follower);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
