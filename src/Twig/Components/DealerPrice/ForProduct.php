<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Twig\Components\DealerPrice;

use App\Entity\DealerPrice;
use App\Repository\DealerPriceRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

/**
 * Store offers of a game, shown in the sidebar of its page.
 */
#[AsTwigComponent(name: 'DealerPrice:ForProduct', template: 'frontend/dealer_price/_list.html.twig')]
final class ForProduct
{
    public int $productId;

    public int $count = 4;

    public function __construct(
        #[Autowire(service: 'app.repository.dealer_price')]
        private readonly DealerPriceRepository $dealerPriceRepository,
    ) {
    }

    /**
     * @return Pagerfanta<DealerPrice>
     */
    #[ExposeInTemplate('dealer_prices')]
    public function getDealerPrices(): Pagerfanta
    {
        $dealerPrices = $this->dealerPriceRepository->createFilterPaginator(['product' => $this->productId]);
        $dealerPrices->setMaxPerPage($this->count);

        return $dealerPrices;
    }
}
