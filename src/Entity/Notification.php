<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 23/10/2015
 * Time: 16:12.
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
    protected $authors;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(
     *      targetEntity="Sylius\Component\Customer\Model\CustomerInterface",
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    protected $recipient;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_read")
     */
    protected $read;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Expose
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Expose
     */
    protected $target;

    /**
     * @var Topic
     *
     * @ORM\ManyToOne(targetEntity="Topic")
     * @Expose
     */
    protected $topic;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface", inversedBy="notifications")
     */
    protected $product;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="Article")
     */
    protected $article;

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

    /**
     * @param CustomerInterface $author
     *
     * @return bool
     */
    public function hasAuthor(CustomerInterface $author): bool
    {
        return $this->authors->contains($author);
    }

    /**
     * @param CustomerInterface $author
     */
    public function addAuthor(CustomerInterface $author): void
    {
        if (!$this->hasAuthor($author)) {
            $this->authors->add($author);
        }
    }

    /**
     * @param CustomerInterface $author
     */
    public function removeAuthor(CustomerInterface $author): void
    {
        $this->authors->removeElement($author);
    }

    /**
     * @return CustomerInterface|null
     */
    public function getRecipient(): ?CustomerInterface
    {
        return $this->recipient;
    }

    /**
     * @param CustomerInterface|null $recipient
     */
    public function setRecipient(?CustomerInterface $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->read;
    }

    /**
     * @param bool $read
     */
    public function setRead(bool $read): void
    {
        $this->read = $read;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * @param string|null $target
     */
    public function setTarget(?string $target): void
    {
        $this->target = $target;
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
    }

    /**
     * @return ProductInterface|null
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
}
