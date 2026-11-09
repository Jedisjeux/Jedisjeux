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

use App\Entity\Post;
use App\Entity\Topic;
use App\Updater\PostCountByTopicUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class PostCountByTopicUpdaterTest extends TestCase
{
    public function testItUpdatesTopicWithPostCount(): void
    {
        $topic = $this->createMock(Topic::class);
        $topic->method('getPosts')->willReturn(new ArrayCollection([
            $this->createStub(Post::class),
            $this->createStub(Post::class),
        ]));

        $topic->expects($this->once())->method('setPostCount')->with(2);

        (new PostCountByTopicUpdater())->update($topic);
    }
}
