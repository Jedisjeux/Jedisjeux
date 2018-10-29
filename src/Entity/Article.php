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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Review\Model\ReviewableInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_article", indexes={
 *      @ORM\Index(name="publishable_idx", columns={"publishable"})
 * })
 */
class Article implements ResourceInterface, ReviewableInterface, RoutedItemInterface
{
    use IdentifiableTrait;
    use Timestampable;

    /**
     * status constants.
     */
    const STATUS_NEW = 'new';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_PENDING_PUBLICATION = 'pending_publication';
    const STATUS_PUBLISHED = 'published';

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishStartDate;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $shortDescription;

    /**
     * @var ArticleImage
     *
     * @ORM\OneToOne(targetEntity="ArticleImage", cascade={"persist", "merge"})
     *
     * @Assert\Valid()
     */
    protected $mainImage;

    /**
     * @var TaxonInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     */
    protected $mainTaxon;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishEndDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $publishable = true;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $imagePath;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $viewCount = 0;

    /**
     * @var SlideShowBlock|null
     *
     * @ORM\OneToOne(targetEntity="SlideShowBlock", cascade={"persist", "merge"})
     */
    protected $slideShowBlock;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface", inversedBy="articles")
     */
    protected $product;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     */
    protected $author;

    /**
     * @var Topic
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Topic", inversedBy="article")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $topic;

    /**
     * @var ArrayCollection
     */
    protected $reviews;

    /**
     * @var Collection|Block[]
     *
     * @ORM\OneToMany(targetEntity="Block", mappedBy="article", cascade={"persist", "merge"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @Assert\Valid()
     */
    protected $blocks;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $averageRating = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $materialRating = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $rulesRating = 0;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
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

    /**
     * @param string|null $code
     */
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

    /**
     * @param string|null $slug
     */
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

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return SlideShowBlock|null
     */
    public function getSlideShowBlock(): ?SlideShowBlock
    {
        return $this->slideShowBlock;
    }

    /**
     * @param SlideShowBlock|null $slideShowBlock
     */
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

    /**
     * @param ProductInterface|null $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * @return CustomerInterface|null
     */
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

    /**
     * @return Topic|null
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    /**
     * @param Topic|null $topic
     */
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

    /**
     * @param Block $block
     *
     * @return bool
     */
    public function hasBlock(Block $block): bool
    {
        return $this->blocks->contains($block);
    }

    /**
     * @param Block $block
     */
    public function addBlock(Block $block): void
    {
        if (!$this->hasBlock($block)) {
            $block->setArticle($this);
            $this->blocks->add($block);
        }
    }

    /**
     * @param Block $block
     */
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

    /**
     * @param ReviewInterface $review
     *
     * @return bool
     */
    public function hasReview(ReviewInterface $review): bool
    {
        return $this->reviews->contains($review);
    }

    /**
     * @param ReviewInterface $review
     */
    public function addReview(ReviewInterface $review): void
    {
        if (!$this->hasReview($review)) {
            $review->setReviewSubject($this);
            $this->reviews->add($review);
        }
    }

    /**
     * @param ReviewInterface $review
     */
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

    /**
     * @return float|null
     */
    public function getMaterialRating(): ?float
    {
        return $this->materialRating;
    }

    /**
     * @param float $materialRating
     */
    public function setMaterialRating(float $materialRating): void
    {
        $this->materialRating = $materialRating;
    }

    /**
     * @return float|null
     */
    public function getRulesRating(): ?float
    {
        return $this->rulesRating;
    }

    /**
     * @param float $rulesRating
     */
    public function setRulesRating(float $rulesRating): void
    {
        $this->rulesRating = $rulesRating;
    }

    /**
     * @return float|null
     */
    public function getLifetimeRating(): ?float
    {
        return $this->lifetimeRating;
    }

    /**
     * @param float $lifetimeRating
     */
    public function setLifetimeRating(float $lifetimeRating): void
    {
        $this->lifetimeRating = $lifetimeRating;
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
     * @return \DateTime|null
     */
    public function getPublishStartDate(): ?\DateTime
    {
        return $this->publishStartDate;
    }

    /**
     * @param \DateTime|null $publishStartDate
     */
    public function setPublishStartDate(?\DateTime $publishStartDate): void
    {
        $this->publishStartDate = $publishStartDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublishEndDate(): ?\DateTime
    {
        return $this->publishEndDate;
    }

    /**
     * @param \DateTime|null $publishEndDate
     */
    public function setPublishEndDate(?\DateTime $publishEndDate): void
    {
        $this->publishEndDate = $publishEndDate;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string|null $shortDescription
     */
    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return bool
     */
    public function isPublishable(): bool
    {
        return $this->publishable;
    }

    /**
     * @param bool $publishable
     */
    public function setPublishable(bool $publishable): void
    {
        $this->publishable = $publishable;
    }

    /**
     * @return string|null
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * @param string|null $imagePath
     */
    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
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
     * @return ArticleImage|null
     */
    public function getMainImage(): ?ArticleImage
    {
        return $this->mainImage;
    }

    /**
     * @param ArticleImage|null $mainImage
     */
    public function setMainImage(?ArticleImage $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    /**
     * @return TaxonInterface|null
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
     * @return bool
     */
    public function isReviewArticle(): bool
    {
        if (null === $this->getMainTaxon()) {
            return false;
        }

        return Taxon::CODE_REVIEW_ARTICLE === $this->getMainTaxon()->getCode();
    }

    /**
     * @return bool
     */
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
        return $this->getTitle();
    }
}
