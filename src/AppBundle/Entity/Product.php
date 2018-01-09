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

use AppBundle\Validator\Constraints as CustomAssert;
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
     * status constants
     */
    const STATUS_NEW = "new";
    const PENDING_TRANSLATION = "pending_translation";
    const PENDING_REVIEW = "pending_review";
    const PENDING_PUBLICATION = "pending_publication";
    const PUBLISHED = "published";

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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_age")
     * @JMS\Groups({"Detailed"})
     *
     * @Assert\Range(
     *      min = 0,
     *      groups={"sylius"}
     * )
     */
    protected $ageMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_player_count")
     * @JMS\Groups({"Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $joueurMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("max_player_count")
     * @JMS\Groups({"Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $joueurMax;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("min_duration")
     * @JMS\Groups({"Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $durationMin;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     *
     * @JMS\Expose
     * @JMS\SerializedName("max_duration")
     * @JMS\Groups({"Detailed"})
     *
     * @Assert\Range(
     *      min = 1,
     *      groups={"sylius"}
     * )
     */
    protected $durationMax;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $durationByPlayer;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $materiel;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $but;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $viewCount = 0;

    /**
     * @var ArrayCollection
     */
    protected $reviews;

    /**
     * @var ArrayCollection|ProductBarcode[]
     *
     * @ORM\OneToMany(targetEntity="ProductBarcode", mappedBy="product", cascade={"persist", "merge", "remove"})
     */
    protected $barcodes;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
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
        $this->code = uniqid('product_');
    }

    /**
     * @param string $name
     * @param bool $updateVariant
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
     * @JMS\Groups({"Detailed"})
     */
    public function getMainImage()
    {
        return $this->getFirstVariant()->getMainImage();
    }

    /**
     * @return ProductVariantImage
     */
    public function getMaterialImage()
    {
        return $this->getFirstVariant()->getMaterialImage();
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
        $sort->orderBy(Array(
            'position' => Criteria::ASC
        ));

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
            foreach($variant->getImages() as $image) {
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
            return $taxonomy !== 'forum';
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
    public function getAgeMin(): ?int
    {
        return $this->ageMin;
    }

    /**
     * @param int|null $ageMin
     */
    public function setAgeMin(?int $ageMin): void
    {
        $this->ageMin = $ageMin;
    }

    /**
     * @return int|null
     */
    public function getJoueurMin(): ?int
    {
        return $this->joueurMin;
    }

    /**
     * @param int|null $joueurMin
     */
    public function setJoueurMin(?int $joueurMin): void
    {
        $this->joueurMin = $joueurMin;
    }

    /**
     * @return int|null
     */
    public function getJoueurMax(): ?int
    {
        return $this->joueurMax;
    }

    /**
     * @param int|null $joueurMax
     */
    public function setJoueurMax(?int $joueurMax): void
    {
        $this->joueurMax = $joueurMax;
    }

    /**
     * @return int|null
     */
    public function getDurationMin(): ?int
    {
        return $this->durationMin;
    }

    /**
     * @param int|null $durationMin
     */
    public function setDurationMin(?int $durationMin): void
    {
        $this->durationMin = $durationMin;
    }

    /**
     * @return int|null
     */
    public function getDurationMax(): ?int
    {
        return $this->durationMax;
    }

    /**
     * @param int|null $durationMax
     */
    public function setDurationMax(?int $durationMax): void
    {
        $this->durationMax = $durationMax;
    }

    /**
     * @return boolean
     */
    public function isDurationByPlayer(): bool
    {
        return $this->durationByPlayer;
    }

    /**
     * @param boolean $durationByPlayer
     */
    public function setDurationByPlayer(bool $durationByPlayer): void
    {
        $this->durationByPlayer = $durationByPlayer;
    }

    /**
     * @return string|null
     */
    public function getMateriel(): ?string
    {
        return $this->materiel;
    }

    /**
     * @param string|null $materiel
     */
    public function setMateriel(?string $materiel): void
    {
        $this->materiel = $materiel;
    }

    /**
     * @return string|null
     */
    public function getBut(): ?string
    {
        return $this->but;
    }

    /**
     * @param string|null $but
     */
    public function setBut($but): void
    {
        $this->but = $but;
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
     * @JMS\Type("ArrayCollection<AppBundle\Entity\Taxon>")
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
     * @JMS\Type("ArrayCollection<AppBundle\Entity\Taxon>")
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
     */
    public function addBarcode($barcode): void
    {
        if (!$this->barcodes->contains($barcode)) {
            $barcode->setProduct($this);
            $this->barcodes->add($barcode);
        }
    }

    /**
     * @param ProductBarcode $barcode
     */
    public function removeBarcode($barcode): void
    {
        $this->barcodes->remove($barcode);
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
     * @return Collection|ReviewInterface[]
     */
    public function getGamePlaysByAuthor(CustomerInterface $author)
    {
        return $this->gamePlays->filter(function (GamePlay $gamePlay) use ($author) {
            return $author === $gamePlay->getAuthor();
        });
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