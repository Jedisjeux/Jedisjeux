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
}
