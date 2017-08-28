<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 18/03/2016
 * Time: 13:25
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
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
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     *
     * @return $this
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int
     */
    public function getPlayerCount()
    {
        return $this->playerCount;
    }

    /**
     * @param int $playerCount
     *
     * @return $this
     */
    public function setPlayerCount($playerCount)
    {
        $this->playerCount = $playerCount;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPlayedAt()
    {
        return $this->playedAt;
    }

    /**
     * @param \DateTime $playedAt
     *
     * @return $this
     */
    public function setPlayedAt($playedAt)
    {
        $this->playedAt = $playedAt;

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
     * @return GamePlayImage[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param GamePlayImage $image
     *
     * @return $this
     */
    public function addImage(GamePlayImage $image)
    {
        if (!$this->images->contains($image)) {
            $image->setGamePlay($this);
            $this->images->add($image);
        }

        return $this;
    }

    /**
     * @param GamePlayImage $image
     *
     * @return $this
     */
    public function removeImage(GamePlayImage $image)
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * @return Player[]|ArrayCollection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param Player $player
     *
     * @return $this
     */
    public function addPlayer(Player $player)
    {
        if (!$this->players->contains($player)) {
            $player->setGamePlay($this);
            $this->players->add($player);
        }

        return $this;
    }

    /**
     * @param Player $player
     *
     * @return $this
     */
    public function removePlayer(Player $player)
    {
        $this->players->remove($player);

        return $this;
    }
}
