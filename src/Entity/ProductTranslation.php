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
use Sylius\Component\Product\Model\ProductTranslation as BaseProductTranslation;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_translation", indexes={
 *      @ORM\Index(name="name_idx", columns={"name"})
 * })
 */
class ProductTranslation extends BaseProductTranslation
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $boxContent;

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string|null $shortDescription
     */
    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return string|null
     */
    public function getBoxContent(): ?string
    {
        return $this->boxContent;
    }

    /**
     * @param string|null $boxContent
     */
    public function setBoxContent(?string $boxContent): void
    {
        $this->boxContent = $boxContent;
    }
}
