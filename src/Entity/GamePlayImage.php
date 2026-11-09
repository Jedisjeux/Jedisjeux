<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/03/16
 * Time: 08:31.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Index;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[ORM\Table(name: 'jdj_game_play_image')]
#[AsResource(
    alias: 'app.game_play_image',
    section: 'frontend',
    operations: [
        new Index(
            path: '/images/parties/',
            template: 'frontend/game_play_image/index.html.twig',
            grid: 'app_frontend_game_play_image',
        ),
    ],
)]
class GamePlayImage extends AbstractImage
{
    /**
     * @var GamePlay
     */
    #[ORM\ManyToOne(targetEntity: GamePlay::class, inversedBy: 'images')]
    private $gamePlay;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private $description;

    public function getGamePlay(): ?GamePlay
    {
        return $this->gamePlay;
    }

    public function setGamePlay(?GamePlay $gamePlay): void
    {
        $this->gamePlay = $gamePlay;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
