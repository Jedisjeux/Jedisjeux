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

namespace App\Tests\Unit\PublicationManager;

use App\Entity\Article;
use App\PublicationManager\ArticlePublicationManager;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

final class ArticlePublicationManagerTest extends TestCase
{
    public function testItStartsAnArticle(): void
    {
        $article = $this->createMock(Article::class);
        $manager = $this->createMock(ObjectManager::class);

        $article->expects($this->once())->method('setPublishStartDate')->with($this->isInstanceOf(\DateTime::class));
        $manager->expects($this->once())->method('flush');

        (new ArticlePublicationManager($manager))->start($article);
    }
}
