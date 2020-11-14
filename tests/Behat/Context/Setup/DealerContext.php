<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Dealer;
use App\Fixture\Factory\DealerExampleFactory;
use Behat\Behat\Context\Context;
use Monofony\Bridge\Behat\Service\SharedStorageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class DealerContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var DealerExampleFactory
     */
    private $dealerFactory;

    /**
     * @var RepositoryInterface
     */
    private $dealerRepository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        DealerExampleFactory $dealerFactory,
        RepositoryInterface $dealerRepository
    ) {
        $this->dealerFactory = $dealerFactory;
        $this->dealerRepository = $dealerRepository;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given there is dealer :name
     *
     * @param string $name
     */
    public function thereIsDealer($name): void
    {
        /** @var Dealer $dealer */
        $dealer = $this->dealerFactory->create([
            'name' => $name,
        ]);

        $this->dealerRepository->add($dealer);
        $this->sharedStorage->set('dealer', $dealer);
    }
}
