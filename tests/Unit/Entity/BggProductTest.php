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

use App\Entity\BggProduct;
use PHPUnit\Framework\TestCase;

final class BggProductTest extends TestCase
{
    private BggProduct $sut;

    protected function setUp(): void
    {
        $this->sut = new BggProduct();
    }

    public function testItsNameIsMutable(): void
    {
        $this->sut->setName('Puerto Rico');
        $this->assertSame('Puerto Rico', $this->sut->getName());
    }

    public function testItsImagePathIsMutable(): void
    {
        $this->sut->setImagePath('/tmp/image.jpg');
        $this->assertSame('/tmp/image.jpg', $this->sut->getImagePath());
    }

    public function testItsReleasedAtYearIsMutable(): void
    {
        $this->sut->setReleasedAtYear('2005');
        $this->assertSame('2005', $this->sut->getReleasedAtYear());
    }

    public function testItsMinDurationIsMutable(): void
    {
        $this->sut->setMinDuration('60');
        $this->assertSame('60', $this->sut->getMinDuration());
    }

    public function testItsMaxDurationIsMutable(): void
    {
        $this->sut->setMaxDuration('120');
        $this->assertSame('120', $this->sut->getMaxDuration());
    }

    public function testItsDescriptionIsMutable(): void
    {
        $this->sut->setDescription('This is an awesome description.');
        $this->assertSame('This is an awesome description.', $this->sut->getDescription());
    }

    public function testItsAgeIsMutable(): void
    {
        $this->sut->setAge('12');
        $this->assertSame('12', $this->sut->getAge());
    }

    public function testItsMinPlayerCountIsMutable(): void
    {
        $this->sut->setMinPlayerCount('2');
        $this->assertSame('2', $this->sut->getMinPlayerCount());
    }

    public function testItsMaxPlayerCountIsMutable(): void
    {
        $this->sut->setMaxPlayerCount('5');
        $this->assertSame('5', $this->sut->getMaxPlayerCount());
    }

    public function testItInitializesMechanismsArrayByDefault(): void
    {
        $this->assertSame([], $this->sut->getMechanisms());
    }

    public function testItAddsMechanisms(): void
    {
        $this->sut->addMechanism('Variable Phase Order');
        $this->assertTrue($this->sut->hasMechanism('Variable Phase Order'));
    }

    public function testItRemovesMechanisms(): void
    {
        $this->sut->addMechanism('Variable Phase Order');
        $this->sut->removeMechanism('Variable Phase Order');
        $this->assertFalse($this->sut->hasMechanism('Variable Phase Order'));
    }

    public function testItInitializesCategoriesArrayByDefault(): void
    {
        $this->assertSame([], $this->sut->getCategories());
    }

    public function testItAddsCategories(): void
    {
        $this->sut->addCategory('Economic');
        $this->assertTrue($this->sut->hasCategory('Economic'));
    }

    public function testItRemovesCategories(): void
    {
        $this->sut->addCategory('Economic');
        $this->sut->removeCategory('Economic');
        $this->assertFalse($this->sut->hasCategory('Economic'));
    }

    public function testItInitializesDesignersArrayByDefault(): void
    {
        $this->assertSame([], $this->sut->getDesigners());
    }

    public function testItAddsDesigners(): void
    {
        $this->sut->addDesigner('Andreas Seyfarth');
        $this->assertTrue($this->sut->hasDesigner('Andreas Seyfarth'));
    }

    public function testItRemovesDesigners(): void
    {
        $this->sut->addDesigner('Andreas Seyfarth');
        $this->sut->removeDesigner('Andreas Seyfarth');
        $this->assertFalse($this->sut->hasDesigner('Andreas Seyfarth'));
    }

    public function testItInitializesArtistsArrayByDefault(): void
    {
        $this->assertSame([], $this->sut->getArtists());
    }

    public function testItAddsArtists(): void
    {
        $this->sut->addArtist('Franz Vohwinkel');
        $this->assertTrue($this->sut->hasArtist('Franz Vohwinkel'));
    }

    public function testItRemovesArtists(): void
    {
        $this->sut->addArtist('Franz Vohwinkel');
        $this->sut->removeArtist('Franz Vohwinkel');
        $this->assertFalse($this->sut->hasArtist('Franz Vohwinkel'));
    }

    public function testItInitializesPublishersArrayByDefault(): void
    {
        $this->assertSame([], $this->sut->getPublishers());
    }

    public function testItAddsPublishers(): void
    {
        $this->sut->addPublisher('Alea');
        $this->assertTrue($this->sut->hasPublisher('Alea'));
    }

    public function testItRemovesPublishers(): void
    {
        $this->sut->addPublisher('Alea');
        $this->sut->removePublisher('Alea');
        $this->assertFalse($this->sut->hasPublisher('Alea'));
    }
}
