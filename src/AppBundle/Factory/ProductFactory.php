<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 29/06/2016
 * Time: 13:38
 */

namespace AppBundle\Factory;

use AppBundle\Entity\Product;
use AppBundle\Utils\BggProduct;
use AppBundle\Utils\ProductFromBggPath;
use Sylius\Component\Product\Factory\ProductFactory as BaseProductFactory;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductFactory extends BaseProductFactory
{
    public function createFromBgg($bggPath)
    {
        /** @var Product $product */
        $product = parent::createNew();

        $bggProduct = new BggProduct($bggPath);

        $product->setName($bggProduct->getName());
        $product->setDescription($bggProduct->getDescription());
        //$product->getMasterVariant()->setReleasedAt($bggProduct->getReleasedAtYear());
        $product->setAgeMin($bggProduct->getAge());
        $product->setDurationMin($bggProduct->getDuration());
        $product->setDurationMax($bggProduct->getDuration());
        $product->setJoueurMin($bggProduct->getNbJoueursMin());
        $product->setJoueurMax($bggProduct->getNbJoueursMax());

        return $product;
    }
}
