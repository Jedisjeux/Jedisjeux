<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\GameAward;
use App\Fixture\Factory\GameAwardExampleFactory;
use Behat\Behat\Context\Context;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\SharedStorageInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class GameAwardContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var GameAwardExampleFactory
     */
    private $gameAwardFactory;

    /**
     * @var RepositoryInterface
     */
    private $gameAwardRepository;

    /**
     * @param SharedStorageInterface  $sharedStorage
     * @param GameAwardExampleFactory $gameAwardFactory
     * @param RepositoryInterface     $gameAwardRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        GameAwardExampleFactory $gameAwardFactory,
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
