<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Transform;

use App\Entity\DealerPrice;
use App\Entity\GameAward;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

class GameAwardContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $gameAwardRepository;

    /**
     */
    public function __construct(RepositoryInterface $gameAwardRepository)
    {
        $this->gameAwardRepository = $gameAwardRepository;
    }

    /**
     * @Transform /^game award "([^"]+)"$/
     * @Transform :gameAward
     */
    public function getGameAwardByName($name)
    {
        /** @var GameAward $gameAward */
        $gameAward = $this->gameAwardRepository->findOneBy(['name' => $name]);

        Assert::notNull(
            $gameAward,
            sprintf('Game award with name "%s" does not exist', $name)
        );

        return $gameAward;
    }
}
