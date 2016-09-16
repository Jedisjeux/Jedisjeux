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
 * @ORM\Table(name="jdj_pub_banner")
 */
class PubBanner extends AbstractImage
{
    /**
     * @var Dealer
     *
     * @ORM\ManyToOne(targetEntity="Dealer", inversedBy="pubBanners")
     */
    protected $dealer;

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
}
