<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Transform;

use App\Entity\DealerPrice;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerPriceContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $dealerPriceRepository;

    /**
     * DealerPriceContext constructor.
     *
     * @param RepositoryInterface $dealerPriceRepository
     */
    public function __construct(RepositoryInterface $dealerPriceRepository)
    {
        $this->dealerPriceRepository = $dealerPriceRepository;
    }

    /**
     * @Transform :dealerPrice
     *
     * @param string $url
     *
     * @return DealerPrice
     */
    public function getDealerPriceByUrl($url)
    {
        /** @var DealerPrice $dealerPrice */
        $dealerPrice = $this->dealerPriceRepository->findOneBy(['url' => $url]);

        Assert::notNull(
            $dealerPrice,
            sprintf('Dealer price with url "%s" does not exist', $url)
        );

        return $dealerPrice;
    }
}
