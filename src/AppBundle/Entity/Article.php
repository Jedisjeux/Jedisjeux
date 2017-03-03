<?php

/**
 * This file is part of Jedisjeux project
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use AppBundle\Document\ArticleContent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Review\Model\ReviewableInterface;
use Sylius\Component\Review\Model\ReviewInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_article", indexes={
 *      @ORM\Index(name="publish_start_date_idx", columns={"publishStartDate"}),
 *      @ORM\Index(name="publishable_idx", columns={"publishable"})
 * })
 */
class Article implements ResourceInterface, ReviewableInterface
{
    use IdentifiableTrait;
    use Timestampable;

    /**
     * status constants
     */
    const STATUS_NEW = "new";
    const STATUS_PENDING_REVIEW = "pending_review";
    const STATUS_PENDING_PUBLICATION = "pending_publication";
    const STATUS_PUBLISHED = "published";

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishStartDate;

    /**
     * @var ArticleImage
     *
     * @ORM\OneToOne(targetEntity="ArticleImage", cascade={"persist", "merge"})
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
     */
    protected $viewCount = 0;

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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Topic", inversedBy="article")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $topic;

    /**
     * @var Collection|Block[]
     *
     * @ORM\OneToMany(targetEntity="Block", mappedBy="article", cascade={"persist", "merge"})
     */
    protected $blocks;

    /**
     * @var ArrayCollection|ArticleReview[]
     *
     * @ORM\OneToMany(targetEntity="ArticleReview", mappedBy="reviewSubject")
     */
    protected $reviews;

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
        $this->blocks = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->status = self::STATUS_NEW;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        $this->product = $product;

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
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     *
     * @return $this
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * @return Block[]|Collection
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param Block $block
     *
     * @return bool
     */
    public function hasBlock(Block $block)
    {
        return $this->blocks->contains($block);
    }

    /**
     * @param Block $block
     *
     * @return $this
     */
    public function addBlock(Block $block)
    {
        if (!$this->hasBlock($block)) {
            $block->setArticle($this);
            $this->blocks->add($block);
        }

        return $this;
    }

    /**
     * @param Block $block
     *
     * @return $this
     */
    public function removeBlock(Block $block)
    {
        $this->blocks->removeElement($block);

        return $this;
    }

    /**
     * @return ArticleReview[]|ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param ReviewInterface $review
     *
     * @return $this
     */
    public function addReview(ReviewInterface $review)
    {
        if (!$this->reviews->contains($review)) {
            $review->setReviewSubject($this);
            $this->reviews->add($review);
        }

        return $this;
    }

    /**
     * @param ReviewInterface $review
     *
     * @return $this
     */
    public function removeReview(ReviewInterface $review)
    {
        $this->reviews->remove($review);

        return $this;
    }

    /**
     * @return float
     */
    public function getAverageRating()
    {
        return $this->averageRating;
    }

    /**
     * @param float $averageRating
     *
     * @return $this
     */
    public function setAverageRating($averageRating)
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    /**
     * @return float
     */
    public function getMaterialRating()
    {
        return $this->materialRating;
    }

    /**
     * @param float $materialRating
     *
     * @return $this
     */
    public function setMaterialRating($materialRating)
    {
        $this->materialRating = $materialRating;

        return $this;
    }

    /**
     * @return float
     */
    public function getRulesRating()
    {
        return $this->rulesRating;
    }

    /**
     * @param float $rulesRating
     *
     * @return $this
     */
    public function setRulesRating($rulesRating)
    {
        $this->rulesRating = $rulesRating;

        return $this;
    }

    /**
     * @return float
     */
    public function getLifetimeRating()
    {
        return $this->lifetimeRating;
    }

    /**
     * @param float $lifetimeRating
     *
     * @return $this
     */
    public function setLifetimeRating($lifetimeRating)
    {
        $this->lifetimeRating = $lifetimeRating;

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
     * @return \DateTime|null
     */
    public function getPublishStartDate()
    {
        return $this->publishStartDate;
    }

    /**
     * @param \DateTime|null $publishStartDate
     *
     * @return $this
     */
    public function setPublishStartDate($publishStartDate)
    {
        $this->publishStartDate = $publishStartDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublishEndDate()
    {
        return $this->publishEndDate;
    }

    /**
     * @param \DateTime|null $publishEndDate
     *
     * @return $this
     */
    public function setPublishEndDate($publishEndDate)
    {
        $this->publishEndDate = $publishEndDate;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublishable()
    {
        return $this->publishable;
    }

    /**
     * @param boolean $publishable
     *
     * @return $this
     */
    public function setPublishable($publishable)
    {
        $this->publishable = $publishable;

        return $this;
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * @param string $imagePath
     *
     * @return $this
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

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
     * @return ArticleImage
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @param ArticleImage $mainImage
     *
     * @return $this
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * @return TaxonInterface
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
     * @return bool
     */
    public function isReviewArticle() {
        if (null === $this->getMainTaxon()) {
            return false;
        }

        return Taxon::CODE_REVIEW_ARTICLE === $this->getMainTaxon()->getCode();
    }

    /**
     * @return bool
     */
    public function isReportArticle() {
        if (null === $this->getMainTaxon()) {
            return false;
        }

        return Taxon::CODE_REPORT_ARTICLE === $this->getMainTaxon()->getCode();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
