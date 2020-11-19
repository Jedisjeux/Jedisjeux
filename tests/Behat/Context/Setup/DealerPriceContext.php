<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Dealer;
use App\Entity\DealerPrice;
use App\Fixture\Factory\DealerPriceExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerPriceContext implements Context
{
    /**
     * @var DealerPriceExampleFactory
     */
    private $dealerPriceFactory;

    /**
     * @var RepositoryInterface
     */
    private $dealerPriceRepository;

    /**
     * DealerContext constructor.
     *
     */
    public function __construct(DealerPriceExampleFactory $dealerPriceFactory, RepositoryInterface $dealerPriceRepository)
    {
        $this->dealerPriceFactory = $dealerPriceFactory;
        $this->dealerPriceRepository = $dealerPriceRepository;
    }

    /**
     * @Given the dealer :dealer sold :product product on :url page
     * @Given /^(this dealer)(?:| also) sold ("([^"]+)" product) on "([^"]+)" page$/
     */
    public function thereIsDealerPriceOnThisPage(Dealer $dealer, ProductInterface $product, $url)
    {
        /** @var DealerPrice $dealerPrice */
        $dealerPrice = $this->dealerPriceFactory->create([
            'name' => $product->getName(),
            'dealer' => $dealer,
            'product' => $product,
            'url' => $url,
        ]);

        $this->dealerPriceRepository->add($dealerPrice);
    }
}
