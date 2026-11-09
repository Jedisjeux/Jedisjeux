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

use App\Entity\FestivalList;
use App\Entity\FestivalListItem;
use App\Entity\Product;
use App\Fixture\Factory\FestivalListExampleFactory;
use Behat\Behat\Context\Context;
use App\Tests\Behat\Bridge\Service\SharedStorageInterface;
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
     * @var FestivalListExampleFactory
     */
    private $festivalListFactory;

    /**
     * @var RepositoryInterface
     */
    private $festivalListRepository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        FestivalListExampleFactory $festivalListFactory,
        RepositoryInterface $festivalListRepository,
        private readonly RepositoryInterface $festivalListItemRepository,
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
        $this->sharedStorage->set('festival_list', $festivalList);
    }

    /**
     * @Given /^(festival list "[^"]+") contains ("[^"]+" product)$/
     */
    public function festivalListContainsProduct(FestivalList $festivalList, Product $product)
    {
        $festivalListItem = new FestivalListItem();
        $festivalListItem->setList($festivalList);
        $festivalListItem->setProduct($product);

        $this->festivalListItemRepository->add($festivalListItem);
    }
}
