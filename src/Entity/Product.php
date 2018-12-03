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

use App\Validator\Constraints as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslationInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Review\Model\ReviewableInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 *
 * @CustomAssert\MaxPlayerCountGreaterThanOrEqualMinPlayer(groups={"sylius"})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Product extends BaseProduct implements ReviewableInterface
{
    /**
     * status constants.
     */
    const STATUS_NEW = 'new';
    const PENDING_TRANSLATION = 'pending_translation';
    const PENDING_REVIEW = 'pending_review';
    const PENDING_PUBLICATION = 'pending_publication';
    const PUBLISHED = 'published';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string")
     */
    protected $status;

    /**
     * @var ArrayCollection|TaxonInterface[]
     *
     * @ORM\ManyToMany(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     * @ORM\JoinTable("sylius_product_taxon",
     *      inverseJoinColumns={@ORM\JoinColumn(name="taxoninterface_id", referencedColumnName="id")}
     * )
     */
    protected $taxons;

    /**
     * @var TaxonInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Taxonomy\Model\TaxonInterface")
     */
    protected $mainTaxon;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_age")
     * @JMS\Groups({"Default", "Detailed"})
     *
     * @Assert\Range(
     *      min = 0,
     *      groups={"sylius"}
     * )
     */
    protected $minAge;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_player_count")
     * @JMS\Groups({"Default", "Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $minPlayerCount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("max_player_count")
     * @JMS\Groups({"Default", "Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $maxPlayerCount;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_duration")
     * @JMS\Groups({"Default", "Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $minDuration;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("max_duration")
     * @JMS\Groups({"Default", "Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $maxDuration;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $durationByPlayer;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $boxContent;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $viewCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $reviewCount = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $commentedReviewCount = 0;

    /**
     * @var ArrayCollection
     */
    protected $reviews;

    /**
     * @var ArrayCollection|ProductBarcode[]
     *
     * @ORM\OneToMany(targetEntity="ProductBarcode", mappedBy="product", cascade={"persist", "merge", "remove"}, orphanRemoval=true)
     */
    protected $barcodes;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @JMS\Expose
     * @JMS\Groups({"Default", "Detailed"})
     */
    protected $averageRating = 0;

    /**
     * @var Collection|Article[]
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="product")
     */
    protected $articles;

    /**
     * @var Collection|GamePlay[]
     *
     * @ORM\OneToMany(targetEntity="GamePlay", mappedBy="product")
     */
    protected $gamePlays;

    /**
     * @var Collection|ProductList[]
     *
     * @ORM\OneToMany(targetEntity="ProductListItem", mappedBy="product")
     */
    protected $listItems;

    /**
     * @var Collection|Notification[]
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="product", cascade={"remove"})
     */
    protected $notifications;

    /**
     * @var Collection|ProductVideo[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProductVideo", orphanRemoval=true, mappedBy="product")
     */
    protected $videos;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->taxons = new ArrayCollection();
        $this->durationByPlayer = false;
        $this->status = self::STATUS_NEW;
        $this->reviews = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->listItems = new ArrayCollection();
        $this->gamePlays = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->barcodes = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->code = uniqid('product_');
    }

    /**
     * @param string $name
     * @param bool   $updateVariant
     */
    public function setName(?string $name, $updateVariant = true): void
    {
        parent::setName($name);

        /** @var ProductVariant $firstVariant */
        $firstVariant = $this->getFirstVariant();

        if ($firstVariant and $updateVariant) {
            $firstVariant->setName($name);
        }
    }

    /**
     * @return string|null
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
     * @return string|null
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Detailed"})
     */
    public function getShortDescription(): ?string
    {
        return $this->getTranslation()->getShortDescription();
    }

    /**
     * @param string|null $shortDescription
     */
    public function setShortDescription(?string $shortDescription): void
    {
        $this->getTranslation()->setShortDescription($shortDescription);
    }

    /**
     * @return ProductVariantImage
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("image")
     * @JMS\Groups({"Default", "Detailed"})
     */
    public function getMainImage(): ?ProductVariantImage
    {
        if (null === $firstVariant = $this->getFirstVariant()) {
            return null;
        }

        return $firstVariant->getMainImage();
    }

    /**
     * @return ProductVariantImage
     */
    public function getMaterialImage(): ?ProductVariantImage
    {
        if (null === $firstVariant = $this->getFirstVariant()) {
            return null;
        }

        return $firstVariant->getMaterialImage();
    }

    /**
     * @return Collection|ProductVariantImage[]
     */
    public function getImages(): Collection
    {
        return $this->getFirstVariant()->getImages();
    }

    /**
     * @return ProductVariantInterface|ProductVariant|null
     */
    public function getFirstVariant(): ?ProductVariantInterface
    {
        if ($this->variants->isEmpty()) {
            return null;
        }

        // todo remove after sylius update (variants will be sorted by position)
        $sort = Criteria::create();
        $sort->orderBy([
            'position' => Criteria::ASC,
        ]);

        return $this->variants->matching($sort)->first();
    }

    /**
     * @param ProductVariantInterface $variant
     */
    public function setFirstVariant(ProductVariantInterface $variant): void
    {
        $firstVariant = $this->getFirstVariant();

        if (null !== $firstVariant) {
            $firstVariant = $variant;
        } else {
            $this->addVariant($variant);
        }
    }

    /**
     * @deprecated
     */
    public function getMasterVariant()
    {
        return $this->getFirstVariant();
    }

    /**
     * @return Collection|ProductVariantImage[]
     */
    public function getImagesOfAllVariants(): Collection
    {
        $collection = new ArrayCollection();

        /** @var ProductVariant $variant */
        foreach ($this->variants as $variant) {
            foreach ($variant->getImages() as $image) {
                $collection->add($image);
            }
        }

        return $collection;
    }

    /**
     * @return \DateTime|null
     */
    public function getReleasedAt(): ?\DateTime
    {
        return $this->getFirstVariant()->getReleasedAt();
    }

    /**
     * @param \DateTime|null $releasedAt
     */
    public function setReleasedAt(?\DateTime $releasedAt): void
    {
        $this->getFirstVariant()->setReleasedAt($releasedAt);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxons($taxonomy = null): Collection
    {
        if (null !== $taxonomy) {
            return $this->taxons->filter(function (TaxonInterface $taxon) use ($taxonomy) {
                return $taxonomy === strtolower($taxon->getRoot()->getCode());
            });
        }

        return $this->taxons->filter(function (TaxonInterface $taxon) use ($taxonomy) {
            return 'forum' !== $taxonomy;
        });
    }

    /**
     * @param TaxonInterface $taxon
     *
     * @return bool
     */
    public function hasTaxon(TaxonInterface $taxon): bool
    {
        return $this->taxons->contains($taxon);
    }

    /**
     * @param TaxonInterface $taxon
     */
    public function addTaxon(TaxonInterface $taxon): void
    {
        if (!$this->hasTaxon($taxon)) {
            $this->taxons->add($taxon);
        }
    }

    /**
     * @param TaxonInterface $taxon
     */
    public function removeTaxon(TaxonInterface $taxon): void
    {
        $this->taxons->removeElement($taxon);
    }

    /**
     * @return TaxonInterface|Taxon|null
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
     * @return int|null
     */
    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    /**
     * @param int|null $minAge
     */
    public function setMinAge(?int $minAge): void
    {
        $this->minAge = $minAge;
    }

    /**
     * @return int|null
     */
    public function getMinPlayerCount(): ?int
    {
        return $this->minPlayerCount;
    }

    /**
     * @param int|null $minPlayerCount
     */
    public function setMinPlayerCount(?int $minPlayerCount): void
    {
        $this->minPlayerCount = $minPlayerCount;
    }

    /**
     * @return int|null
     */
    public function getMaxPlayerCount(): ?int
    {
        return $this->maxPlayerCount;
    }

    /**
     * @param int|null $maxPlayerCount
     */
    public function setMaxPlayerCount(?int $maxPlayerCount): void
    {
        $this->maxPlayerCount = $maxPlayerCount;
    }

    /**
     * @return int|null
     */
    public function getMinDuration(): ?int
    {
        return $this->minDuration;
    }

    /**
     * @param int|null $minDuration
     */
    public function setMinDuration(?int $minDuration): void
    {
        $this->minDuration = $minDuration;
    }

    /**
     * @return int|null
     */
    public function getMaxDuration(): ?int
    {
        return $this->maxDuration;
    }

    /**
     * @param int|null $maxDuration
     */
    public function setMaxDuration(?int $maxDuration): void
    {
        $this->maxDuration = $maxDuration;
    }

    /**
     * @return bool
     */
    public function isDurationByPlayer(): bool
    {
        return $this->durationByPlayer;
    }

    /**
     * @param bool $durationByPlayer
     */
    public function setDurationByPlayer(bool $durationByPlayer): void
    {
        $this->durationByPlayer = $durationByPlayer;
    }

    /**
     * @return string|null
     */
    public function getBoxContent(): ?string
    {
        return $this->boxContent;
    }

    /**
     * @param string|null $boxContent
     */
    public function setBoxContent(?string $boxContent): void
    {
        $this->boxContent = $boxContent;
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
     * @return int
     */
    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    /**
     * @param int $reviewCount
     */
    public function setReviewCount(int $reviewCount): void
    {
        $this->reviewCount = $reviewCount;
    }

    /**
     * @return int
     */
    public function getCommentedReviewCount(): int
    {
        return $this->commentedReviewCount;
    }

    /**
     * @param int $commentedReviewCount
     */
    public function setCommentedReviewCount(int $commentedReviewCount): void
    {
        $this->commentedReviewCount = $commentedReviewCount;
    }

    /**
     * @return Collection|Person[]
     */
    public function getDesigners(): Collection
    {
        return $this->getFirstVariant()->getDesigners();
    }

    /**
     * @return Collection|Person[]
     */
    public function getArtists(): Collection
    {
        return $this->getFirstVariant()->getArtists();
    }

    /**
     * @return Collection|Person[]
     */
    public function getPublishers(): Collection
    {
        return $this->getFirstVariant()->getPublishers();
    }

    /**
     * @return ArrayCollection
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Detailed"})
     * @JMS\Type("ArrayCollection<App\Entity\Taxon>")
     */
    public function getMechanisms(): Collection
    {
        return $this->getTaxons('mechanisms');
    }

    /**
     * @param TaxonInterface $mechanism
     */
    public function addMechanism(TaxonInterface $mechanism): void
    {
        $this->addTaxon($mechanism);
    }

    /**
     * @param TaxonInterface $mechanism
     */
    public function removeMechanism(TaxonInterface $mechanism): void
    {
        $this->removeTaxon($mechanism);
    }

    /**
     * @return Collection
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Detailed"})
     * @JMS\Type("ArrayCollection<App\Entity\Taxon>")
     */
    public function getThemes(): Collection
    {
        return $this->getTaxons('themes');
    }

    /**
     * @param TaxonInterface $theme
     */
    public function addTheme(TaxonInterface $theme): void
    {
        $this->addTaxon($theme);
    }

    /**
     * @param TaxonInterface $theme
     */
    public function removeTheme(TaxonInterface $theme): void
    {
        $this->removeTaxon($theme);
    }

    /**
     * @return Collection
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
     * @return Collection|ReviewInterface[]
     */
    public function getRatings(): Collection
    {
        return $this->reviews->filter(function (ReviewInterface $review) {
            return null === $review->getComment();
        });
    }

    /**
     * @return Collection|ReviewInterface[]
     */
    public function getCommentedReviews(): Collection
    {
        return $this->reviews->filter(function (ReviewInterface $review) {
            return null !== $review->getComment();
        });
    }

    /**
     * @return float
     */
    public function getAverageRating(): ?float
    {
        return $this->averageRating;
    }

    /**
     * @param float $averageRating
     */
    public function setAverageRating(float $averageRating): void
    {
        $this->averageRating = $averageRating;
    }

    /**
     * @return ProductBarcode[]|Collection
     */
    public function getBarcodes(): Collection
    {
        return $this->barcodes;
    }

    /**
     * @param ProductBarcode $barcode
     *
     * @return bool
     */
    public function hasBarcode(ProductBarcode $barcode): bool
    {
        return $this->barcodes->contains($barcode);
    }

    /**
     * @param ProductBarcode $barcode
     */
    public function addBarcode(ProductBarcode $barcode): void
    {
        if (!$this->hasBarcode($barcode)) {
            $barcode->setProduct($this);
            $this->barcodes->add($barcode);
        }
    }

    /**
     * @param ProductBarcode $barcode
     */
    public function removeBarcode(ProductBarcode $barcode): void
    {
        $this->barcodes->removeElement($barcode);
        $barcode->setProduct(null);
    }

    /**
     * @return Article[]|Collection
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * @return Article[]|Collection
     */
    public function getPublishedArticles(): Collection
    {
        return $this->articles->filter(function (Article $article) {
            return Article::STATUS_PUBLISHED === $article->getStatus();
        });
    }

    /**
     * @return GamePlay[]|Collection
     */
    public function getGamePlays(): Collection
    {
        return $this->gamePlays;
    }

    /**
     * @return GamePlay[]|Collection
     */
    public function getGamePlaysWithTopic(): Collection
    {
        return $this->gamePlays->filter(function (GamePlay $gamePlay) {
            return null !== $gamePlay->getTopic();
        });
    }

    /**
     * @return Collection|ReviewInterface[]
     */
    public function getGamePlaysByAuthor(CustomerInterface $author)
    {
        return $this->gamePlays->filter(function (GamePlay $gamePlay) use ($author) {
            return $author === $gamePlay->getAuthor();
        });
    }

    /**
     * @return ProductVideo[]|Collection
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    /**
     * @param ProductVideo $video
     *
     * @return bool
     */
    public function hasVideo(ProductVideo $video): bool
    {
        return $this->videos->contains($video);
    }

    /**
     * @param ProductVideo $video
     */
    public function addVideo(ProductVideo $video): void
    {
        if (!$this->hasVideo($video)) {
            $this->videos->add($video);
            $video->setProduct($this);
        }
    }

    /**
     * @param ProductVideo $video
     */
    public function removeVideo(ProductVideo $video): void
    {
        $this->videos->removeElement($video);
        $video->setProduct(null);
    }

    /**
     * {@inheritdoc}
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Default", "Autocomplete"})
     */
    public function getCode(): ?string
    {
        return parent::getCode();
    }

    /**
     * {@inheritdoc}
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Default", "Autocomplete"})
     */
    public function getName(): ?string
    {
        return parent::getName();
    }

    /**
     * {@inheritdoc}
     *
     * @JMS\VirtualProperty
     * @JMS\Groups({"Default"})
     */
    public function getSlug(): ?string
    {
        return parent::getSlug();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    protected function createTranslation(): ProductTranslationInterface
    {
        return new ProductTranslation();
    }
}
