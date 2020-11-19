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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true, options={"unsigned"=true})
     */
    protected $duration;

    /**
     * @var int
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
     * @ORM\OneToOne(targetEntity="App\Entity\Topic", inversedBy="gamePlay")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $topic;

    /**
     * @var ArrayCollection|GamePlayImage[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\GamePlayImage", mappedBy="gamePlay", cascade={"persist", "merge", "remove"})
     *
     * @Assert\Valid()
     */
    protected $images;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $imageCount = 0;

    /**
     * @var ArrayCollection|Player[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="gamePlay", cascade={"persist", "merge", "remove"})
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

    public function getCode(): ?string
    {
        return $this->code;
    }

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

    public function setDuration(?int $duration): void
    {
        $this->duration = $duration;
    }

    public function getPlayerCount(): ?int
    {
        return $this->playerCount;
    }

    public function setPlayerCount(?int $playerCount): void
    {
        $this->playerCount = $playerCount;
    }

    public function getPlayedAt(): ?\DateTime
    {
        return $this->playedAt;
    }

    public function setPlayedAt(?\DateTime $playedAt): void
    {
        $this->playedAt = $playedAt;
    }

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

    public function hasImage(GamePlayImage $image): bool
    {
        return $this->images->contains($image);
    }

    public function addImage(GamePlayImage $image): void
    {
        if (!$this->hasImage($image)) {
            $image->setGamePlay($this);
            $this->images->add($image);
        }
    }

    public function removeImage(GamePlayImage $image): void
    {
        $this->images->removeElement($image);
    }

    public function getImageCount(): int
    {
        return $this->imageCount;
    }

    public function setImageCount(int $imageCount): void
    {
        $this->imageCount = $imageCount;
    }

    /**
     * @return Player[]|Collection
     */
    public function getPlayers(): ?Collection
    {
        return $this->players;
    }

    public function hasPlayer(Player $player): bool
    {
        return $this->players->contains($player);
    }

    public function addPlayer(Player $player): void
    {
        if (!$this->hasPlayer($player)) {
            $player->setGamePlay($this);
            $this->players->add($player);
        }
    }

    public function removePlayer(Player $player): void
    {
        $this->players->removeElement($player);
    }
}
