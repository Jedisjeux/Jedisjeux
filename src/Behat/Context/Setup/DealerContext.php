<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Entity\Dealer;
use App\Fixture\Factory\DealerExampleFactory;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerContext implements Context
{
    /**
     * @var DealerExampleFactory
     */
    private $dealerFactory;

    /**
     * @var RepositoryInterface
     */
    private $dealerRepository;

    /**
     * DealerContext constructor.
     *
     * @param DealerExampleFactory $dealerFactory
     * @param RepositoryInterface  $dealerRepository
     */
    public function __construct(DealerExampleFactory $dealerFactory, RepositoryInterface $dealerRepository)
    {
        $this->dealerFactory = $dealerFactory;
        $this->dealerRepository = $dealerRepository;
    }

    /**
     * @Given there is dealer :name
     *
     * @param string $name
     */
    public function thereIsDealer($name)
    {
        /** @var Dealer $dealer */
        $dealer = $this->dealerFactory->create([
            'name' => $name,
        ]);

        $this->dealerRepository->add($dealer);
    }
}
