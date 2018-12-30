<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity
 * @ORM\Table("jdj_year_award")
 */
class YearAward implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $year;

    /**
     * @var GameAward|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\GameAward")
     */
    private $award;

    /**
     * @var string|null
     *
     * @Gedmo\Slug(handlers={
     *      @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\RelativeSlugHandler", options={
     *          @Gedmo\SlugHandlerOption(name="relationField", value="award"),
     *          @Gedmo\SlugHandlerOption(name="relationSlugField", value="slug"),
     *          @Gedmo\SlugHandlerOption(name="separator", value="/")
     *      })
     * }, separator="-", updatable=true, fields={"year"})
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $slug;

    /**
     * @return string|null
     */
    public function getYear(): ?string
    {
        return $this->year;
    }

    /**
     * @param string|null $year
     */
    public function setYear(?string $year): void
    {
        $this->year = $year;
    }

    /**
     * @return GameAward|null
     */
    public function getAward(): ?GameAward
    {
        return $this->award;
    }

    /**
     * @param GameAward|null $award
     */
    public function setAward(?GameAward $award): void
    {
        $this->award = $award;
    }

    public function getName(): ?string
    {
        if (null === $this->getAward()) {
            return null;
        }

        return trim($this->getAward()->getName().' '.$this->getYear());
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param null|string $slug
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }
}
