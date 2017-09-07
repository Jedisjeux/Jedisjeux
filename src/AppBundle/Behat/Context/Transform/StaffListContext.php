<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Transform;

use AppBundle\Entity\Dealer;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StaffListContext implements Context
{
    /**
     * @var RepositoryInterface
     */
    private $staffListRepository;

    /**
     * PersonContext constructor.
     *
     * @param RepositoryInterface $staffListRepository
     */
    public function __construct(RepositoryInterface $staffListRepository)
    {
        $this->staffListRepository = $staffListRepository;
    }

    /**
     * @Transform /^staff list "([^"]+)"$/
     * @Transform :staffList
     *
     * @param string $name
     *
     * @return Dealer
     */
    public function getStaffListByName($name)
    {
        /** @var Dealer $dealer */
        $dealer = $this->staffListRepository->findOneBy(['name' => $name]);

        Assert::notNull(
            $dealer,
            sprintf('Dealer with name "%s" does not exist', $name)
        );

        return $dealer;
    }
}
