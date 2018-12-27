<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\GameAward;
use App\Entity\YearAward;
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class YearAwardContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    private $yearAwardFactory;

    /**
     * @var RepositoryInterface
     */
    private $yearAwardRepository;

    /**
     * @param SharedStorageInterface  $sharedStorage
     * @param ExampleFactoryInterface $yearAwardFactory
     * @param RepositoryInterface     $yearAwardRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $yearAwardFactory,
        RepositoryInterface $yearAwardRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->yearAwardFactory = $yearAwardFactory;
        $this->yearAwardRepository = $yearAwardRepository;
    }

    /**
     * @Given /^(this game award) has(?:| also) been celebrated in "([^"]+)"$/
     *
     * @param string $name
     */
    public function thereIsYearAward(GameAward $gameAward, $year)
    {
        /** @var YearAward $yearAward */
        $yearAward = $this->yearAwardFactory->create([
            'award' => $gameAward,
            'year' => $year,
        ]);

        $this->yearAwardRepository->add($yearAward);
        $this->sharedStorage->set('year_award', $yearAward);
    }
}
