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

use App\Behat\Service\SharedStorageInterface;
use App\Entity\FestivalList;
use App\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    private $festivalListFactory;

    /**
     * @var RepositoryInterface
     */
    private $festivalListRepository;

    /**
     * @param SharedStorageInterface  $sharedStorage
     * @param ExampleFactoryInterface $festivalListFactory
     * @param RepositoryInterface     $festivalListRepository
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ExampleFactoryInterface $festivalListFactory,
        RepositoryInterface $festivalListRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->festivalListFactory = $festivalListFactory;
        $this->festivalListRepository = $festivalListRepository;
    }

    /**
     * @Given there is festival list :name
     *
     * @param string $name
     */
    public function thereIsFestivalList($name)
    {
        /** @var FestivalList $festivalList */
        $festivalList = $this->festivalListFactory->create([
            'name' => $name,
        ]);

        $this->festivalListRepository->add($festivalList);
    }
}
