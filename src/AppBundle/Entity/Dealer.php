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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_dealer")
 */
class Dealer implements ResourceInterface
{
    use IdentifiableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var DealerImage
     *
     * @ORM\OneToOne(targetEntity="DealerImage", cascade={"persist"})
     */
    protected $image;

    /**
     * @var PriceList
     *
     * @ORM\OneToOne(targetEntity="PriceList", mappedBy="dealer", cascade={"persist"})
     */
    protected $priceList;

    /**
     * @var ArrayCollection|PubBanner[]
     *
     * @ORM\OneToMany(targetEntity="PubBanner", mappedBy="dealer", cascade={"persist", "remove"})
     */
    protected $pubBanners;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return DealerImage
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param DealerImage $image
     *
     * @return $this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPriceList()
    {
        return null !== $this->priceList;
    }

    /**
     * @return PriceList
     */
    public function getPriceList()
    {
        return $this->priceList;
    }

    /**
     * @param PriceList $priceList
     *
     * @return $this
     */
    public function setPriceList($priceList)
    {
        $this->priceList = $priceList;

        return $this;
    }

    /**
     * @return PubBanner[]|ArrayCollection
     */
    public function getPubBanners()
    {
        return $this->pubBanners;
    }

    /**
     * @param PubBanner $pubBanner
     *
     * @return $this
     */
    public function addPubBanner($pubBanner)
    {
        if (!$this->pubBanners->contains($pubBanner)) {
            $pubBanner->setDealer($this);
            $this->pubBanners->add($pubBanner);
        }

        return $this;
    }

    /**
     * @param PubBanner $pubBanner
     *
     * @return $this
     */
    public function removePubBanner($pubBanner)
    {
        $this->pubBanners->remove($pubBanner);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName();
    }
}
