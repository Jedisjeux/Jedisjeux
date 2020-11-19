<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Validator\Constraints as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslationInterface;
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
class Product extends BaseProduct implements ProductInterface
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
     * @ORM\OneToMany(targetEntity="App\Entity\ProductVideo", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="product")
     *
     * @Assert\Valid(groups={"sylius"})
     */
    private $videos;

    /**
     * @var Collection|ProductFile[]
     *
     * @ORM\OneToMany(targetEntity="ProductFile", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $files;

    /**
     * @var Collection|YearAward[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\YearAward", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $yearAwards;

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
        $this->files = new ArrayCollection();
        $this->yearAwards = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\Groups({"Detailed"})
     */
    public function getShortDescription(): ?string
    {
        return $this->getTranslation()->getShortDescription();
    }

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
     * {@inheritdoc}
     */
    public function getFirstVariant(): ?ProductVariantInterface
    {
        if (!$this->hasVariants()) {
            return null;
        }

        return $this->variants->first();
    }

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

    public function getReleasedAt(): ?\DateTime
    {
        return $this->getFirstVariant()->getReleasedAt();
    }

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

    public function hasTaxon(TaxonInterface $taxon): bool
    {
        return $this->taxons->contains($taxon);
    }

    public function addTaxon(TaxonInterface $taxon): void
    {
        if (!$this->hasTaxon($taxon)) {
            $this->taxons->add($taxon);
        }
    }

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

    public function setMainTaxon(?TaxonInterface $mainTaxon): void
    {
        $this->mainTaxon = $mainTaxon;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(?int $minAge): void
    {
        $this->minAge = $minAge;
    }

    public function getMinPlayerCount(): ?int
    {
        return $this->minPlayerCount;
    }

    public function setMinPlayerCount(?int $minPlayerCount): void
    {
        $this->minPlayerCount = $minPlayerCount;
    }

    public function getMaxPlayerCount(): ?int
    {
        return $this->maxPlayerCount;
    }

    public function setMaxPlayerCount(?int $maxPlayerCount): void
    {
        $this->maxPlayerCount = $maxPlayerCount;
    }

    public function getMinDuration(): ?int
    {
        return $this->minDuration;
    }

    public function setMinDuration(?int $minDuration): void
    {
        $this->minDuration = $minDuration;
    }

    public function getMaxDuration(): ?int
    {
        return $this->maxDuration;
    }

    public function setMaxDuration(?int $maxDuration): void
    {
        $this->maxDuration = $maxDuration;
    }

    public function isDurationByPlayer(): bool
    {
        return $this->durationByPlayer;
    }

    public function setDurationByPlayer(bool $durationByPlayer): void
    {
        $this->durationByPlayer = $durationByPlayer;
    }

    public function getBoxContent(): ?string
    {
        return $this->boxContent;
    }

    public function setBoxContent(?string $boxContent): void
    {
        $this->boxContent = $boxContent;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    /**
     * {@inheritdoc}
     */
    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    /**
     * {@inheritdoc}
     */
    public function setReviewCount(int $reviewCount): void
    {
        $this->reviewCount = $reviewCount;
    }

    public function getCommentedReviewCount(): int
    {
        return $this->commentedReviewCount;
    }

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

    public function addMechanism(TaxonInterface $mechanism): void
    {
        $this->addTaxon($mechanism);
    }

    public function removeMechanism(TaxonInterface $mechanism): void
    {
        $this->removeTaxon($mechanism);
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\Groups({"Detailed"})
     * @JMS\Type("ArrayCollection<App\Entity\Taxon>")
     */
    public function getThemes(): Collection
    {
        return $this->getTaxons('themes');
    }

    public function addTheme(TaxonInterface $theme): void
    {
        $this->addTaxon($theme);
    }

    public function removeTheme(TaxonInterface $theme): void
    {
        $this->removeTaxon($theme);
    }

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
            $this->reviews->add($review);
        }
    }

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

    public function hasBarcode(ProductBarcode $barcode): bool
    {
        return $this->barcodes->contains($barcode);
    }

    public function addBarcode(ProductBarcode $barcode): void
    {
        if (!$this->hasBarcode($barcode)) {
            $barcode->setProduct($this);
            $this->barcodes->add($barcode);
        }
    }

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

    public function hasVideo(ProductVideo $video): bool
    {
        return $this->videos->contains($video);
    }

    public function addVideo(ProductVideo $video): void
    {
        if (!$this->hasVideo($video)) {
            $this->videos->add($video);
            $video->setProduct($this);
        }
    }

    public function removeVideo(ProductVideo $video): void
    {
        $this->videos->removeElement($video);
        $video->setProduct(null);
    }

    /**
     * @return ProductFile[]|Collection
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function hasFile(ProductFile $file): bool
    {
        return $this->files->contains($file);
    }

    public function addFile(ProductFile $file): void
    {
        if (!$this->hasFile($file)) {
            $this->files->add($file);
            $file->setProduct($this);
        }
    }

    public function removeFile(ProductFile $file): void
    {
        $this->files->removeElement($file);
        $file->setProduct(null);
    }

    /**
     * @return YearAward[]|Collection
     */
    public function getYearAwards(): Collection
    {
        return $this->yearAwards;
    }

    public function hasYearAwards(): bool
    {
        return !$this->yearAwards->isEmpty();
    }

    public function hasYearAward(YearAward $yearAward): bool
    {
        return $this->yearAwards->contains($yearAward);
    }

    public function addYearAward(YearAward $yearAward): void
    {
        if (!$this->hasYearAward($yearAward)) {
            $this->yearAwards->add($yearAward);
            $yearAward->setProduct($this);
        }
    }

    public function removeYearAward(YearAward $yearAward): void
    {
        $this->yearAwards->removeElement($yearAward);
        $yearAward->setProduct(null);
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
