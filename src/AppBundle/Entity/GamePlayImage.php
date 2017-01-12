<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/03/16
 * Time: 08:31
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_game_play_image")
 */
class GamePlayImage extends AbstractImage
{
    /**
     * @var GamePlay
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GamePlay", inversedBy="images")
     */
    protected $gamePlay;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @return GamePlay
     */
    public function getGamePlay()
    {
        return $this->gamePlay;
    }

    /**
     * @param GamePlay $gamePlay
     *
     * @return $this
     */
    public function setGamePlay($gamePlay)
    {
        $this->gamePlay = $gamePlay;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
