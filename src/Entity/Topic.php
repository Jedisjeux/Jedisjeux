<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Twig\Context\ForumTaxonContextFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
#[ORM\Entity]
#[JMS\ExclusionPolicy('all')]
#[ORM\Table('jdj_topic')]
#[AsResource(
    alias: 'app.topic',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: [
        'subheader' => 'app.ui.manage_topics',
        'templates' => ['form' => 'backend/topic/_form.html.twig'],
    ],
    operations: [
        new Index(grid: 'app_backend_topic', vars: ['icon' => 'comment']),
        new Create(redirectToRoute: 'app_backend_topic_update'),
        new Update(redirectToRoute: 'app_backend_topic_update'),
        new Delete(),
        new BulkDelete(),
    ],
)]
#[AsResource(
    alias: 'app.topic',
    section: 'frontend',
    operations: [
        new Index(path: '/topics/', template: 'frontend/topic/index.html.twig', grid: 'app_frontend_topic'),
        new Create(
            path: '/topics/new',
            template: 'frontend/topic/create.html.twig',
            redirectToRoute: 'app_frontend_post_index_by_topic',
            redirectArguments: ['topicId' => 'resource.id'],
        ),
        new Create(
            shortName: 'create_for_taxon',
            eventShortName: 'create',
            path: '/topics/{taxonId}/new',
            template: 'frontend/topic/create.html.twig',
            factoryMethod: 'createForTaxon',
            factoryArguments: [
                'taxon' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.taxon').find(request.attributes.get('taxonId')))",
            ],
            redirectToRoute: 'app_frontend_post_index_by_topic',
            redirectArguments: ['topicId' => 'resource.id'],
        ),
        new Update(
            path: '/topics/{id}/edit',
            template: 'frontend/topic/update.html.twig',
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'author': user ? user.getCustomer() : null}",
            ],
            redirectToRoute: 'app_frontend_post_index_by_topic',
            redirectArguments: ['topicId' => 'resource.id'],
        ),
        new Delete(
            path: '/topics/{id}',
            methods: ['DELETE'],
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'author': user ? user.getCustomer() : null}",
            ],
            redirectToRoute: 'app_frontend_topic_index',
        ),
        new Index(
            shortName: 'index_by_taxon',
            path: '/topics/{slug}',
            routeRequirements: ['slug' => '[^?]+'],
            template: 'frontend/topic/index.html.twig',
            grid: 'app_frontend_topic_from_taxon',
            twigContextFactory: ForumTaxonContextFactory::class,
        ),
    ],
)]
class Topic implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', unique: true)]
    protected $code;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[JMS\Expose]
    protected $title;

    /**
     * @var CustomerInterface
     */
    #[ORM\ManyToOne(targetEntity: CustomerInterface::class)]
    #[ORM\JoinColumn(nullable: false)]
    protected $author;

    /**
     * @var Post
     */
    #[ORM\OneToOne(targetEntity: Post::class, inversedBy: 'parent', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Assert\Valid]
    protected $mainPost;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'topic', cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected $posts;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    #[JMS\Expose]
    protected $postCount;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    protected $viewCount = 0;

    /**
     * @var \DateTime
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $lastPostCreatedAt;

    /**
     * @var Taxon
     */
    #[ORM\ManyToOne(targetEntity: TaxonInterface::class)]
    protected $mainTaxon;

    /**
     * @var GamePlay
     */
    #[ORM\OneToOne(targetEntity: GamePlay::class, mappedBy: 'topic')]
    protected $gamePlay;

    /**
     * @var Article
     */
    #[ORM\OneToOne(targetEntity: Article::class, mappedBy: 'topic')]
    protected $article;

    /**
     * @var ArrayCollection|CustomerInterface[]
     */
    #[ORM\ManyToMany(targetEntity: CustomerInterface::class)]
    #[ORM\JoinTable(name: 'jdj_topic_follower', inverseJoinColumns: [new ORM\JoinColumn(name: 'customerinterface_id', referencedColumnName: 'id')])]
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getPostCount(): int
    {
        return $this->postCount;
    }

    public function setPostCount(int $postCount): void
    {
        $this->postCount = $postCount;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    public function getLastPostCreatedAt(): ?\DateTime
    {
        return $this->lastPostCreatedAt;
    }

    public function setLastPostCreatedAt(?\DateTime $lastPostCreatedAt): void
    {
        $this->lastPostCreatedAt = $lastPostCreatedAt;
    }

    public function getFirstPost(): ?Post
    {
        $firstPost = $this->posts->first();

        return $firstPost ?: null;
    }

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

    public function setAuthor(?CustomerInterface $author): void
    {
        $this->author = $author;
    }

    public function getMainPost(): ?Post
    {
        return $this->mainPost;
    }

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

    public function addPost(Post $post): void
    {
        if (!$this->hasPost($post)) {
            $this->posts->add($post);
            $post->setTopic($this);
        }
    }

    public function removePost(Post $post): void
    {
        $this->posts->removeElement($post);
        $post->setTopic(null);
    }

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

    public function setMainTaxon(?TaxonInterface $mainTaxon): void
    {
        $this->mainTaxon = $mainTaxon;
    }

    public function getGamePlay(): ?GamePlay
    {
        return $this->gamePlay;
    }

    public function setGamePlay(?GamePlay $gamePlay): void
    {
        $this->gamePlay = $gamePlay;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

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

    public function addFollower(CustomerInterface $follower): void
    {
        if (!$this->hasFollower($follower)) {
            $this->followers->add($follower);
        }
    }

    public function removeFollower(CustomerInterface $follower): void
    {
        $this->followers->removeElement($follower);
    }

    public function hasFollower(CustomerInterface $follower): bool
    {
        return $this->followers->contains($follower);
    }

    /**
     * @param bool $nullForFirstPage
     */
    public function getLastPageNumber($nullForFirstPage = true): ?int
    {
        $pageNumber = ceil($this->postCount / 10);

        if ($nullForFirstPage) {
            return $pageNumber > 1 ? $pageNumber : null;
        }

        return $pageNumber;
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
    }
}
