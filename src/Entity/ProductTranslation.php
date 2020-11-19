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
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $shortDescription;

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }
}
