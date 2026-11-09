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

namespace App\Tests\Unit\Entity;

use App\Entity\Taxon;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Taxonomy\Model\Taxon as BaseTaxon;

final class TaxonTest extends TestCase
{
    private Taxon $taxon;

    protected function setUp(): void
    {
        $this->taxon = new Taxon();
    }

    public function testItExtendsATaxonModel(): void
    {
        $this->assertInstanceOf(BaseTaxon::class, $this->taxon);
    }

    public function testItHasZeroTopicCountByDefault(): void
    {
        $this->assertSame(0, $this->taxon->getTopicCount());
    }

    public function testItsTopicCountIsMutable(): void
    {
        $this->taxon->setTopicCount(42);

        $this->assertSame(42, $this->taxon->getTopicCount());
    }

    public function testItHasZeroProductCountByDefault(): void
    {
        $this->assertSame(0, $this->taxon->getProductCount());
    }

    public function testItsProductCountIsMutable(): void
    {
        $this->taxon->setProductCount(666);

        $this->assertSame(666, $this->taxon->getProductCount());
    }

    public function testItIsPublicByDefault(): void
    {
        $this->assertTrue($this->taxon->isPublic());
    }

    public function testItInitializesChildTaxonCollectionByDefault(): void
    {
        $this->assertInstanceOf(Collection::class, $this->taxon->getChildren());
    }
}
