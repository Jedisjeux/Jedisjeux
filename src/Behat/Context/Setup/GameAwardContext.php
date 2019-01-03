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
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class GameAwardContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    private $gameAwardFactory;

    /**
     * @var RepositoryInterface
     */
    private $gameAwardRepository;

    /**
     * @param SharedStorageInterface  $sharedStorage
     * @param ExampleFactoryInterface $gameAwardFactory
     * @param RepositoryInterface     $gameAwardRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $gameAwardFactory,
        RepositoryInterface $gameAwardRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->gameAwardFactory = $gameAwardFactory;
        $this->gameAwardRepository = $gameAwardRepository;
    }

    /**
     * @Given there is (also )a game award :name
     *
     * @param string $name
     */
    public function thereIsGameAward($name)
    {
        /** @var GameAward $gameAward */
        $gameAward = $this->gameAwardFactory->create([
            'name' => $name,
        ]);

        $this->gameAwardRepository->add($gameAward);
        $this->sharedStorage->set('game_award', $gameAward);
    }
}
