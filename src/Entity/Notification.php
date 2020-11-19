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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Customer\Model\CustomerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_notification")
 *
 * @ExclusionPolicy("all")
 */
class Notification implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var CustomerInterface[]|Collection
     *
     * @ORM\ManyToMany(
     *      targetEntity="Sylius\Component\Customer\Model\CustomerInterface"
     * )
     * @ORM\JoinTable(
     *     name="jdj_notification_author",
     *     inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")}
     * )
     *
     * @Expose
     */
    private $authors;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(
     *      targetEntity="Sylius\Component\Customer\Model\CustomerInterface",
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipient;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_read")
     */
    private $read;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Expose
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Expose
     */
    private $target;

    /**
     * @var Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @Expose
     */
    private $topic;

    /**
     * @var ProductInterface|null
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface", inversedBy="notifications")
     */
    private $product;

    /**
     * @var Article|null
     *
     * @ORM\ManyToOne(targetEntity="Article")
     */
    private $article;

    /**
     * @var ProductBox|null
     *
     * @ORM\ManyToOne(targetEntity="ProductBox")
     */
    private $productBox;

    /**
     * @var ProductFile|null
     *
     * @ORM\ManyToOne(targetEntity="ProductFile")
     */
    private $productFile;

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->read = false;
        $this->authors = new ArrayCollection();
    }

    /**
     * @return CustomerInterface[]|Collection
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function hasAuthor(CustomerInterface $author): bool
    {
        return $this->authors->contains($author);
    }

    public function addAuthor(CustomerInterface $author): void
    {
        if (!$this->hasAuthor($author)) {
            $this->authors->add($author);
        }
    }

    public function removeAuthor(CustomerInterface $author): void
    {
        $this->authors->removeElement($author);
    }

    public function getRecipient(): ?CustomerInterface
    {
        return $this->recipient;
    }

    public function setRecipient(?CustomerInterface $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function setRead(bool $read): void
    {
        $this->read = $read;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): void
    {
        $this->target = $target;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): void
    {
        $this->topic = $topic;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }

    public function getProductBox(): ?ProductBox
    {
        return $this->productBox;
    }

    public function setProductBox(?ProductBox $productBox): void
    {
        $this->productBox = $productBox;
    }

    public function getProductFile(): ?ProductFile
    {
        return $this->productFile;
    }

    public function setProductFile(?ProductFile $productFile): void
    {
        $this->productFile = $productFile;
    }
}
