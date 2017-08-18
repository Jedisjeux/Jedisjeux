<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Entity\Dealer;
use AppBundle\Entity\DealerPrice;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerPriceContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    private $dealerPriceFactory;

    /**
     * @var RepositoryInterface
     */
    private $dealerPriceRepository;

    /**
     * DealerContext constructor.
     *
     * @param ExampleFactoryInterface $dealerPriceFactory
     * @param RepositoryInterface $dealerPriceRepository
     */
    public function __construct(ExampleFactoryInterface $dealerPriceFactory, RepositoryInterface $dealerPriceRepository)
    {
        $this->dealerPriceFactory = $dealerPriceFactory;
        $this->dealerPriceRepository = $dealerPriceRepository;
    }

    /**
     * @Given the dealer :dealer sold :product product priced at :price
     */
    public function thereIsDealerPrice(Dealer $dealer, ProductInterface $product, $price)
    {
        /** @var DealerPrice $dealerPrice */
        $dealerPrice = $this->dealerPriceFactory->create([
            'dealer' => $dealer,
            'product' => $product,
            'price' => $price,
        ]);

        $this->dealerPriceRepository->add($dealerPrice);
    }
}
