<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_price_list")
 */
class PriceList implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var Dealer
     *
     * @ORM\OneToOne(targetEntity="Dealer", inversedBy="priceList")
     */
    protected $dealer;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $path;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $headers = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $delimiter = ';';

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", name="is_utf8")
     */
    protected $utf8 = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

    public function getDealer(): ?Dealer
    {
        return $this->dealer;
    }

    public function setDealer(?Dealer $dealer): void
    {
        $this->dealer = $dealer;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function hasHeaders(): bool
    {
        return $this->headers;
    }

    public function setHeaders(bool $headers): void
    {
        $this->headers = $headers;
    }

    public function getDelimiter(): ?string
    {
        return $this->delimiter;
    }

    public function setDelimiter(?string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }

    public function isUtf8(): bool
    {
        return $this->utf8;
    }

    public function setUtf8(bool $utf8): void
    {
        $this->utf8 = $utf8;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
