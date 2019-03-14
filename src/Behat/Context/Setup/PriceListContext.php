<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Context\Setup;

use App\Behat\Service\SharedStorageInterface;
use App\Entity\Dealer;
use App\Entity\PriceList;
use Behat\Behat\Context\Context;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class PriceListContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var FactoryInterface
     */
    private $priceListFactory;

    /**
     * @var RepositoryInterface
     */
    private $priceListRepository;

    /**
     * @var MinkParameters
     */
    private $minkParameters;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param FactoryInterface       $priceListFactory
     * @param RepositoryInterface    $priceListRepository
     * @param MinkParameters         $minkParameters
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $priceListFactory,
        RepositoryInterface $priceListRepository,
        MinkParameters $minkParameters
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->priceListFactory = $priceListFactory;
        $this->priceListRepository = $priceListRepository;
        $this->minkParameters = $minkParameters;
    }

    /**
     * @Given /^(this dealer) has(?:| also) a price list with path "([^"]+)"$/
     */
    public function thisDealerHasAPriceListWithPath(Dealer $dealer, string $path)
    {
        $filesPath = $this->getParameter('files_path');

        /** @var PriceList $priceList */
        $priceList = $this->priceListFactory->createNew();
        $priceList->setDealer($dealer);
        $priceList->setPath($filesPath.$path);
        $priceList->setActive(true);

        $this->priceListRepository->add($priceList);
        $this->sharedStorage->set('price_list', $priceList);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    private function getParameter(string $name): ?string
    {
        return $this->minkParameters[$name] ?? null;
    }
}
