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
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", unique=true)
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
     *
     * @Assert\Valid()
     */
    protected $mainPost;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Post", mappedBy="topic", cascade={"persist", "remove"},  orphanRemoval=true)
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
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastPostCreatedAt;

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
     * @ORM\JoinTable(name="jdj_topic_follower",
     *      inverseJoinColumns={@ORM\JoinColumn(name="customerinterface_id", referencedColumnName="id")}
     * )
     */
    protected $followers;

    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->code = uniqid('topic_');
        $this->posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->postCount = 0;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getPostCount(): int
    {
        return $this->postCount;
    }

    /**
     * @param int $postCount
     */
    public function setPostCount(int $postCount): void
    {
        $this->postCount = $postCount;
    }

    /**
     * @return int
     */
    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    /**
     * @param int $viewCount
     */
    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastPostCreatedAt(): ?\DateTime
    {
        return $this->lastPostCreatedAt;
    }

    /**
     * @param \DateTime|null $lastPostCreatedAt
     */
    public function setLastPostCreatedAt(?\DateTime $lastPostCreatedAt): void
    {
        $this->lastPostCreatedAt = $lastPostCreatedAt;
    }

    /**
     * @return Post|null
     */
    public function getFirstPost(): ?Post
    {
        $firstPost = $this->posts->first();

        return $firstPost ?: null;
    }

    /**
     * @return Post|null
     */
    public function getLastPost(): ?Post
    {
        $lastPost = $this->posts->last();

        return $lastPost ?: null;
    }

    /**
     * @return CustomerInterface|Customer|null
     */
    public function getAuthor(): ?CustomerInterface
    {
        return $this->author;
    }

    /**
     * @param CustomerInterface|null $author
     */
    public function setAuthor(?CustomerInterface $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Post|null
     */
    public function getMainPost(): ?Post
    {
        return $this->mainPost;
    }

    /**
     * @param Post|null $mainPost
     */
    public function setMainPost(?Post $mainPost): void
    {
        $this->mainPost = $mainPost;
    }

    /**
     * @return Post[]|Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     */
    public function addPost(Post $post): void
    {
        if (!$this->hasPost($post)) {
            $this->posts->add($post);
            $post->setTopic($this);
        }
    }

    /**
     * @param Post $post
     */
    public function removePost(Post $post): void
    {
        $this->posts->removeElement($post);
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function hasPost(Post $post): bool
    {
        return $this->posts->contains($post);
    }

    /**
     * @return Taxon|TaxonInterface|null
     */
    public function getMainTaxon(): ?TaxonInterface
    {
        return $this->mainTaxon;
    }

    /**
     * @param TaxonInterface|null $mainTaxon
     */
    public function setMainTaxon(?TaxonInterface $mainTaxon): void
    {
        $this->mainTaxon = $mainTaxon;
    }

    /**
     * @return GamePlay|null
     */
    public function getGamePlay(): ?GamePlay
    {
        return $this->gamePlay;
    }

    /**
     * @param GamePlay|null $gamePlay
     */
    public function setGamePlay(?GamePlay $gamePlay): void
    {
        $this->gamePlay = $gamePlay;
    }

    /**
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article|null $article
     */
    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }

    /**
     * @return Collection|CustomerInterface[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    /**
     * @param CustomerInterface $follower
     */
    public function addFollower(CustomerInterface $follower): void
    {
        if (!$this->hasFollower($follower)) {
            $this->followers->add($follower);
        }
    }

    /**
     * @param CustomerInterface $follower
     */
    public function removeFollower(CustomerInterface $follower): void
    {
        $this->followers->removeElement($follower);
    }

    /**
     * @param CustomerInterface $follower
     *
     * @return bool
     */
    public function hasFollower(CustomerInterface $follower): bool
    {
        return $this->followers->contains($follower);
    }

    /**
     * @param bool $nullForFirstPage
     *
     * @return int|null
     */
    public function getLastPageNumber($nullForFirstPage = true): ?int
    {
        $pageNumber = (int) ceil($this->postCount / 10);

        if ($nullForFirstPage) {
            return $pageNumber > 1 ? $pageNumber : null;
        }

        return $pageNumber;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }
}
