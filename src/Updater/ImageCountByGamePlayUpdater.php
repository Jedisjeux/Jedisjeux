<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Updater;

use App\Entity\GamePlay;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ImageCountByGamePlayUpdater
{
    public function update(GamePlay $gamePlay)
    {
        $imageCount = $gamePlay->getImages()->count();
        $gamePlay->setImageCount($imageCount);
    }
}
