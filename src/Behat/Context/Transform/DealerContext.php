<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Transform;

use App\Entity\Dealer;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $dealerRepository;

    /**
     * PersonContext constructor.
     *
     * @param RepositoryInterface $dealerRepository
     */
    public function __construct(RepositoryInterface $dealerRepository)
    {
        $this->dealerRepository = $dealerRepository;
    }

    /**
     * @Transform /^dealer "([^"]+)"$/
     * @Transform :dealer
     *
     * @param string $name
     *
     * @return Dealer
     */
    public function getDealerByName($name)
    {
        /** @var Dealer $dealer */
        $dealer = $this->dealerRepository->findOneBy(['name' => $name]);

        Assert::notNull(
            $dealer,
            sprintf('Dealer with name "%s" does not exist', $name)
        );

        return $dealer;
    }
}
