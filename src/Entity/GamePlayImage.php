<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/03/16
 * Time: 08:31.
 */

namespace App\Entity;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\GamePlay", inversedBy="images")
     */
    protected $gamePlay;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @return GamePlay|null
     */
    public function getGamePlay(): ?GamePlay
    {
        return $this->gamePlay;
    }

    /**
     * @param GamePlay|null $gamePlay
     */
    public function setGamePlay(?GamePlay $gamePlay): void
    {
        $this->gamePlay = $gamePlay;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
