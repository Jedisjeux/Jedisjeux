<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Setup;

use App\Entity\GamePlay;
use App\Entity\GamePlayImage;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;

final class GamePlayImageContext implements Context
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @Given /^(this game play) has(?:| also) an image described as "([^"]+)"$/
     */
    public function thisGamePlayHasAnImage(GamePlay $gamePlay, string $description): void
    {
        $image = new GamePlayImage();
        $image->setPath('game-play.jpg');
        $image->setDescription($description);
        $gamePlay->addImage($image);

        $this->entityManager->flush();
    }
}
