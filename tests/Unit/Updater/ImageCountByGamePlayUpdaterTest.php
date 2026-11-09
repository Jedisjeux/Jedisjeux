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

namespace App\Tests\Unit\Updater;

use App\Entity\GamePlay;
use App\Entity\GamePlayImage;
use App\Updater\ImageCountByGamePlayUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class ImageCountByGamePlayUpdaterTest extends TestCase
{
    public function testItUpdatesGamePlayWithImageCount(): void
    {
        $gamePlay = $this->createMock(GamePlay::class);
        $gamePlay->method('getImages')->willReturn(new ArrayCollection([$this->createStub(GamePlayImage::class)]));

        $gamePlay->expects($this->once())->method('setImageCount')->with(1);

        (new ImageCountByGamePlayUpdater())->update($gamePlay);
    }
}
