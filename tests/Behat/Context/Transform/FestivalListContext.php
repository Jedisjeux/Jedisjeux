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

use App\Entity\Dealer;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $festivalListRepository;

    /**
     * PersonContext constructor.
     *
     */
    public function __construct(RepositoryInterface $festivalListRepository)
    {
        $this->festivalListRepository = $festivalListRepository;
    }

    /**
     * @Transform /^festival list "([^"]+)"$/
     * @Transform :festivalList
     *
     * @param string $name
     *
     * @return Dealer
     */
    public function getFestivalListByName($name)
    {
        /** @var Dealer $dealer */
        $dealer = $this->festivalListRepository->findOneBy(['name' => $name]);

        Assert::notNull(
            $dealer,
            sprintf('Dealer with name "%s" does not exist', $name)
        );

        return $dealer;
    }
}
