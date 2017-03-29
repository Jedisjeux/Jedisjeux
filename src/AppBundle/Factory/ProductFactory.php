<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductVariant;
use AppBundle\Utils\BggProduct;
use AppBundle\Utils\ProductFromBggPath;
use Sylius\Component\Product\Factory\ProductFactory as BaseProductFactory;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductFactory extends BaseProductFactory
{
    /**
     * @param string $bggPath
     *
     * @return Product
     */
    public function createFromBgg($bggPath)
    {
        /** @var Product $product */
        $product = parent::createWithVariant();

        $bggProduct = new BggProduct($bggPath);

        $product->setName($bggProduct->getName());
        $product->setDescription($bggProduct->getDescription());
        $releasedAt = \DateTime::createFromFormat('Y-m-d', $bggProduct->getReleasedAtYear(). '-01-01');

        if (false !== $releasedAt) {
            $product->getFirstVariant()
                ->setReleasedAt($releasedAt)
                ->setReleasedAtPrecision(ProductVariant::RELEASED_AT_PRECISION_ON_YEAR);
        }

        $product->setAgeMin($bggProduct->getAge());
        $product->setDurationMin($bggProduct->getDuration());
        $product->setDurationMax($bggProduct->getDuration());
        $product->setJoueurMin($bggProduct->getNbJoueursMin());
        $product->setJoueurMax($bggProduct->getNbJoueursMax());

        return $product;
    }
}
