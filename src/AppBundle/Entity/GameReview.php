<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/02/16
 * Time: 23:15
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;
use JDJ\JeuBundle\Entity\Jeu;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_game_review")
 */
class GameReview implements ResourceInterface
{
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
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\GameRate")
     */
    protected $rate;

    /**
     * GameReview constructor.
     */
    public function __construct()
    {
        $this->rate = new GameRate();
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
     * @return Jeu
     */
    public function getGame()
    {
        return $this->getRate()->getGame();
    }

    /**
     * @param Jeu $game
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
     * @return $this
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->getRate()->getCreatedBy();
    }
}
