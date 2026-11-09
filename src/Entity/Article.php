<?php

/*
 * This file is part of Jedisjeux project
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Twig\Context\TaxonContextFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Review\Model\ReviewableInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Resource\Metadata\ApplyStateMachineTransition;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[ORM\Table(name: 'jdj_article')]
#[ORM\Index(name: 'publishable_idx', columns: ['publishable'])]
#[AsResource(
    alias: 'app.article',
    section: 'backend',
    templatesDir: 'backend/crud',
    routePrefix: '/admin',
    vars: [
        'subheader' => 'app.ui.manage_articles',
        'templates' => ['form' => 'backend/article/_form.html.twig'],
    ],
    operations: [
        new Index(grid: 'app_backend_article', vars: ['icon' => 'newspaper']),
        new Create(redirectToRoute: 'app_backend_article_update'),
        new Update(
            redirectToRoute: 'app_backend_article_update',
            vars: [
                'templates' => [
                    'form' => 'backend/article/_form.html.twig',
                    'toolbar' => 'backend/article/_toolbar.html.twig',
                ],
            ],
        ),
        new Delete(),
        new Index(
            shortName: 'per_product_index',
            path: 'products/{productId}/articles/',
            template: 'backend/crud/index.html.twig',
            grid: 'app_backend_article_for_product',
        ),
        new Create(
            shortName: 'create_for_product',
            eventShortName: 'create',
            path: 'articles/products/{productId}/new',
            template: 'backend/crud/create.html.twig',
            factoryMethod: 'createForProduct',
            factoryArguments: [
                'product' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.product').find(request.attributes.get('productId')))",
            ],
            redirectToRoute: 'app_backend_article_update',
            vars: [
                'route' => [
                    'name' => 'app_backend_article_create_for_product',
                    'parameters' => ['productId' => '$productId'],
                ],
            ],
        ),
        new Create(
            shortName: 'create_for_product_review',
            eventShortName: 'create',
            path: 'articles/reviews/{productId}/new',
            template: 'backend/crud/create.html.twig',
            factoryMethod: 'createReviewArticleForProduct',
            factoryArguments: [
                'product' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.product').find(request.attributes.get('productId')))",
            ],
            redirectToRoute: 'app_backend_article_update',
            vars: [
                'route' => [
                    'name' => 'app_backend_article_create_for_product_review',
                    'parameters' => ['productId' => '$productId'],
                ],
            ],
        ),
        new ApplyStateMachineTransition(
            path: 'articles/{id}/ask_for_review',
            methods: ['PATCH'],
            stateMachineGraph: 'app_article',
            stateMachineTransition: 'ask_for_review',
            redirectToRoute: 'app_backend_article_update',
        ),
        new ApplyStateMachineTransition(
            path: 'articles/{id}/ask_for_publication',
            methods: ['PATCH'],
            stateMachineGraph: 'app_article',
            stateMachineTransition: 'ask_for_publication',
            redirectToRoute: 'app_backend_article_update',
        ),
        new ApplyStateMachineTransition(
            path: 'articles/{id}/publish',
            methods: ['PATCH'],
            stateMachineGraph: 'app_article',
            stateMachineTransition: 'publish',
            redirectToRoute: 'app_backend_article_update',
        ),
    ],
)]
#[AsResource(
    alias: 'app.article',
    section: 'frontend',
    operations: [
        new Index(
            path: '/articles/',
            template: 'frontend/article/index.html.twig',
            grid: 'app_frontend_article',
        ),
        new Index(
            shortName: 'index_by_product',
            path: '/jeu-de-societe/{productSlug}/articles/',
            template: 'frontend/article/index.html.twig',
            grid: 'app_frontend_article',
        ),
        new Index(
            shortName: 'index_by_taxon',
            path: '/articles/{slug}',
            routeRequirements: ['slug' => '[^?]+'],
            template: 'frontend/article/indexByTaxon.html.twig',
            grid: 'app_frontend_article',
            twigContextFactory: TaxonContextFactory::class,
        ),
        new Show(
            path: '/article/{slug}',
            template: 'frontend/article/show.html.twig',
            repositoryMethod: 'findOneBySlug',
            repositoryArguments: [
                'slug' => "@=request.attributes.get('slug')",
                'showUnpublished' => "@=is_granted('ROLE_ARTICLE_MANAGER')",
            ],
        ),
    ],
)]
class Article implements ResourceInterface, ReviewableInterface, RoutedItemInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * status constants.
     */
    const STATUS_NEW = 'new';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_PENDING_PUBLICATION = 'pending_publication';
    const STATUS_PUBLISHED = 'published';

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', unique: true)]
    protected $code;

    /**
     * @var string
     */
    #[Gedmo\Slug(fields: ['title'])]
    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    protected $slug;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    protected $title;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $publishStartDate;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text', nullable: true)]
    protected $shortDescription;

    /**
     * @var ArticleImage
     */
    #[ORM\OneToOne(targetEntity: ArticleImage::class, cascade: ['persist', 'merge'])]
    #[Assert\Valid]
    protected $mainImage;

    /**
     * @var TaxonInterface
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Taxonomy\Model\TaxonInterface::class)]
    protected $mainTaxon;

    /**
     * @var \DateTime|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $publishEndDate;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    protected $publishable = true;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    protected $status;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected $imagePath;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    protected $viewCount = 0;

    /**
     * @var SlideShowBlock|null
     */
    #[ORM\OneToOne(targetEntity: SlideShowBlock::class, cascade: ['persist', 'merge'])]
    protected $slideShowBlock;

    /**
     * @var ProductInterface
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Product\Model\ProductInterface::class, inversedBy: 'articles')]
    protected $product;

    /**
     * @var CustomerInterface
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Customer\Model\CustomerInterface::class)]
    protected $author;

    /**
     * @var Topic
     */
    #[ORM\OneToOne(targetEntity: \App\Entity\Topic::class, inversedBy: 'article')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    protected $topic;

    /**
     * @var ArrayCollection
     */
    protected $reviews;

    /**
     * @var Collection|Block[]
     */
    #[ORM\OneToMany(targetEntity: Block::class, mappedBy: 'article', cascade: ['persist', 'merge'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    #[Assert\Valid]
    protected $blocks;

    /**
     * @var float
     */
    #[ORM\Column(type: 'float')]
    protected $averageRating = 0;

    /**
     * @var float
     */
    #[ORM\Column(type: 'float')]
    protected $materialRating = 0;

    /**
     * @var float
     */
    #[ORM\Column(type: 'float')]
    protected $rulesRating = 0;

    /**
     * @var float
     */
    #[ORM\Column(type: 'float')]
    protected $lifetimeRating = 0;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->publishable = false;
        $this->code = uniqid('article_');
        $this->blocks = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->status = self::STATUS_NEW;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->getTitle();
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getSlideShowBlock(): ?SlideShowBlock
    {
        return $this->slideShowBlock;
    }

    public function setSlideShowBlock(?SlideShowBlock $slideShowBlock): void
    {
        $this->slideShowBlock = $slideShowBlock;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    public function getAuthor(): ?CustomerInterface
    {
        return $this->author;
    }

    /**
     * @param CustomerInterface $author
     */
    public function setAuthor(?CustomerInterface $author): void
    {
        $this->author = $author;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): void
    {
        $this->topic = $topic;

        if ($this !== $topic->getArticle()) {
            $topic->setArticle($this);
        }
    }

    /**
     * @return Block[]|Collection
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function hasBlock(Block $block): bool
    {
        return $this->blocks->contains($block);
    }

    public function addBlock(Block $block): void
    {
        if (!$this->hasBlock($block)) {
            $block->setArticle($this);
            $this->blocks->add($block);
        }
    }

    public function removeBlock(Block $block): void
    {
        $this->blocks->removeElement($block);
    }

    /**
     * @return ArticleReview[]|Collection
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function hasReview(ReviewInterface $review): bool
    {
        return $this->reviews->contains($review);
    }

    public function addReview(ReviewInterface $review): void
    {
        if (!$this->hasReview($review)) {
            $review->setReviewSubject($this);
            $this->reviews->add($review);
        }
    }

    public function removeReview(ReviewInterface $review): void
    {
        $this->reviews->removeElement($review);
    }

    /**
     * {@inheritdoc}
     */
    public function getAverageRating(): ?float
    {
        return $this->averageRating;
    }

    /**
     * {@inheritdoc}
     */
    public function setAverageRating(float $averageRating): void
    {
        $this->averageRating = $averageRating;
    }

    public function getMaterialRating(): ?float
    {
        return $this->materialRating;
    }

    public function setMaterialRating(float $materialRating): void
    {
        $this->materialRating = $materialRating;
    }

    public function getRulesRating(): ?float
    {
        return $this->rulesRating;
    }

    public function setRulesRating(float $rulesRating): void
    {
        $this->rulesRating = $rulesRating;
    }

    public function getLifetimeRating(): ?float
    {
        return $this->lifetimeRating;
    }

    public function setLifetimeRating(float $lifetimeRating): void
    {
        $this->lifetimeRating = $lifetimeRating;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getPublishStartDate(): ?\DateTime
    {
        return $this->publishStartDate;
    }

    public function setPublishStartDate(?\DateTime $publishStartDate): void
    {
        $this->publishStartDate = $publishStartDate;
    }

    public function getPublishEndDate(): ?\DateTime
    {
        return $this->publishEndDate;
    }

    public function setPublishEndDate(?\DateTime $publishEndDate): void
    {
        $this->publishEndDate = $publishEndDate;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function isPublishable(): bool
    {
        return $this->publishable;
    }

    public function setPublishable(bool $publishable): void
    {
        $this->publishable = $publishable;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    public function getMainImage(): ?ArticleImage
    {
        return $this->mainImage;
    }

    public function setMainImage(?ArticleImage $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    public function getMainTaxon(): ?TaxonInterface
    {
        return $this->mainTaxon;
    }

    public function setMainTaxon(?TaxonInterface $mainTaxon): void
    {
        $this->mainTaxon = $mainTaxon;
    }

    public function isReviewArticle(): bool
    {
        if (null === $this->getMainTaxon()) {
            return false;
        }

        return Taxon::CODE_REVIEW_ARTICLE === $this->getMainTaxon()->getCode();
    }

    public function isReportArticle(): bool
    {
        if (null === $this->getMainTaxon()) {
            return false;
        }

        return Taxon::CODE_REPORT_ARTICLE === $this->getMainTaxon()->getCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedItemTitle()
    {
        return $this->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedItemDescription()
    {
        return $this->getShortDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedItemPubDate()
    {
        return $this->getPublishStartDate();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedItemRouteName()
    {
        return 'app_frontend_article_show';
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedItemRouteParameters()
    {
        return [
            'slug' => $this->getSlug(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFeedItemUrlAnchor()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->getTitle();
    }
}
