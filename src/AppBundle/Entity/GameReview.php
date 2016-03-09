<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/02/16
 * Time: 23:15
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JDJ\CoreBundle\Entity\Like;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_game_review")
 */
class GameReview implements ResourceInterface
{
    use TimestampableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var GameRate
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\GameRate", cascade={"persist"})
     */
    protected $rate;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JDJ\CoreBundle\Entity\Like", mappedBy="gameReview")
     */
    private $likes;

    /**
     * GameReview constructor.
     */
    public function __construct()
    {
        $this->rate = new GameRate();
        $this->likes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getGame()
    {
        return $this->getRate()->getGame();
    }

    /**
     * @param ProductInterface $game
     *
     * @return $this
     */
    public function setGame($game)
    {
        return $this->getRate()->setGame($game);
    }

    /**
     * @return GameRate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param GameRate $rate
     *
     * @return $this
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

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
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param ArrayCollection $likes
     * @return $this
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * TODO use array collection filter
     *
     * @return int
     */
    public function getNbLikes()
    {
        $nb = 0;
        /** @var Like $like */
        foreach ($this->getLikes() as $like) {
            if ($like->isLike()) {
                $nb ++;
            }
        }
        return $nb;
    }

    /**
     * TODO use array collection filter
     *
     * @return int
     */
    public function getNbDislikes()
    {
        $nb = 0;
        /** @var Like $like */
        foreach ($this->getLikes() as $like) {
            if (false === $like->isLike()) {
                $nb ++;
            }
        }
        return $nb;
    }
}
