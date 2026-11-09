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

namespace App\Tests\Unit\Factory;

use App\Entity\FestivalList;
use App\Entity\FestivalListItem;
use App\Factory\FestivalListItemFactory;
use PHPUnit\Framework\TestCase;

final class FestivalListItemFactoryTest extends TestCase
{
    private FestivalListItemFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new FestivalListItemFactory(FestivalListItem::class);
    }

    public function testItCanCreatesAFestivalListItem(): void
    {
        $this->assertInstanceOf(FestivalListItem::class, $this->factory->createNew());
    }

    public function testItCanCreatesAFestivalListItemForAList(): void
    {
        $list = $this->createStub(FestivalList::class);

        $this->assertSame($list, $this->factory->createForList($list)->getList());
    }
}
