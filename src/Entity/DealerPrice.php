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
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_dealer_price")
 */
class DealerPrice implements ResourceInterface
{
    use IdentifiableTrait,
        Timestampable;

    const STATUS_PRE_ORDER = 'pre_order';
    const STATUS_AVAILABLE = 'available';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';

    /**
     * @var Dealer
     *
     * @ORM\ManyToOne(targetEntity="Dealer")
     */
    protected $dealer;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $barcode;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status;

    public function getDealer(): ?Dealer
    {
        return $this->dealer;
    }

    public function setDealer(?Dealer $dealer): void
    {
        $this->dealer = $dealer;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
