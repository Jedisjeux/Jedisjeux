<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var boolean
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
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_utf8")
     */
    protected $utf8 = true;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

    /**
     * @return Dealer|null
     */
    public function getDealer(): ?Dealer
    {
        return $this->dealer;
    }

    /**
     * @param Dealer|null $dealer
     */
    public function setDealer(?Dealer $dealer): void
    {
        $this->dealer = $dealer;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return boolean
     */
    public function hasHeaders(): bool
    {
        return $this->headers;
    }

    /**
     * @param boolean $headers
     */
    public function setHeaders(bool $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return string|null
     */
    public function getDelimiter(): ?string
    {
        return $this->delimiter;
    }

    /**
     * @param string|null $delimiter
     */
    public function setDelimiter(?string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @return bool
     */
    public function isUtf8(): bool
    {
        return $this->utf8;
    }

    /**
     * @param bool $utf8
     */
    public function setUtf8(bool $utf8): void
    {
        $this->utf8 = $utf8;
    }

    /**
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
