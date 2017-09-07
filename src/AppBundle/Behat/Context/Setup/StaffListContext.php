<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Behat\Service\SharedStorageInterface;
use AppBundle\Entity\Dealer;
use AppBundle\Entity\StaffList;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StaffListContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    private $staffListFactory;

    /**
     * @var RepositoryInterface
     */
    private $staffListRepository;

    /**
     * @param $sharedStorage
     * @param ExampleFactoryInterface $staffListFactory
     * @param RepositoryInterface $staffListRepository
     */
    public function __construct($sharedStorage, ExampleFactoryInterface $staffListFactory, RepositoryInterface $staffListRepository)
    {
        $this->sharedStorage = $sharedStorage;
        $this->staffListFactory = $staffListFactory;
        $this->staffListRepository = $staffListRepository;
    }

    /**
     * @Given there is staff list :name
     *
     * @param string $name
     */
    public function thereIsStaffList($name)
    {
        /** @var StaffList $staffList */
        $staffList = $this->staffListFactory->create([
            'name' => $name,
        ]);

        $this->staffListRepository->add($staffList);
    }
}
