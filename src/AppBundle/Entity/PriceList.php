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

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table("jdj_price_list")
 */
class PriceList
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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

    /**
     * @return Dealer
     */
    public function getDealer()
    {
        return $this->dealer;
    }

    /**
     * @param Dealer $dealer
     *
     * @return $this
     */
    public function setDealer($dealer)
    {
        $this->dealer = $dealer;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return boolean
     */
    public function hasHeaders()
    {
        return $this->headers;
    }

    /**
     * @param boolean $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }
}
