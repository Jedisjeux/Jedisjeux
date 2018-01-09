<?php

/**
 * This file is part of Jedisjeux project
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

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
     * @var integer
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
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     */
    public function setProduct(?Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int|null $price
     */
    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string|null
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param string|null $barcode
     */
    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
    }


    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
