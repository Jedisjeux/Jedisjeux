<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/03/2016
 * Time: 13:25
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_game_play")
 */
class GamePlay implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $code;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    protected $duration;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $playerCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    protected $playedAt;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Product\Model\ProductInterface", inversedBy="gamePlays")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

    /**
     * @var CustomerInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Customer\Model\CustomerInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $author;

    /**
     * @var Topic
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Topic", inversedBy="gamePlay")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $topic;

    /**
     * @var ArrayCollection|GamePlayImage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GamePlayImage", mappedBy="gamePlay", cascade={"persist", "merge", "remove"})
     *
     * @Assert\Valid()
     */
    protected $images;

    /**
     * @var ArrayCollection|Player[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Player", mappedBy="gamePlay", cascade={"persist", "merge", "remove"})
     */
    protected $players;

    /**
     * GamePlay constructor.
     */
    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->playedAt = new \DateTime();
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
     * @return int
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int|null $duration
     */
    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return int|null
     */
    public function getPlayerCount(): ?int
    {
        return $this->playerCount;
    }

    /**
     * @param int|null $playerCount
     */
    public function setPlayerCount(?int $playerCount): void
    {
        $this->playerCount = $playerCount;
    }

    /**
     * @return \DateTime|null
     */
    public function getPlayedAt(): ?\DateTime
    {
        return $this->playedAt;
    }

    /**
     * @param \DateTime|null $playedAt
     */
    public function setPlayedAt(?\DateTime $playedAt): void
    {
        $this->playedAt = $playedAt;
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
     * @return CustomerInterface|null
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

        if ($this !== $topic->getGamePlay()) {
            $topic->setGamePlay($this);
        }
    }

    /**
     * @return GamePlayImage[]|Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param GamePlayImage $image
     *
     * @return bool
     */
    public function hasImage(GamePlayImage $image): bool
    {
        return $this->images->contains($image);
    }

    /**
     * @param GamePlayImage $image
     */
    public function addImage(GamePlayImage $image): void
    {
        if (!$this->hasImage($image)) {
            $image->setGamePlay($this);
            $this->images->add($image);
        }
    }

    /**
     * @param GamePlayImage $image
     */
    public function removeImage(GamePlayImage $image): void
    {
        $this->images->removeElement($image);
    }

    /**
     * @return Player[]|Collection
     */
    public function getPlayers(): ?Collection
    {
        return $this->players;
    }

    /**
     * @param Player $player
     *
     * @return bool
     */
    public function hasPlayer(Player $player): bool
    {
        return $this->players->contains($player);
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void
    {
        if (!$this->hasPlayer($player)) {
            $player->setGamePlay($this);
            $this->players->add($player);
        }
    }

    /**
     * @param Player $player
     */
    public function removePlayer(Player $player): void
    {
        $this->players->removeElement($player);
    }
}
