<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Dealer;
use App\Entity\PriceList;
use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\SharedStorageInterface;
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
     * @var ObjectManager
     */
    private $priceListManager;

    /**
     * @var MinkParameters
     */
    private $minkParameters;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param FactoryInterface       $priceListFactory
     * @param RepositoryInterface    $priceListRepository
     * @param ObjectManager          $priceListManager
     * @param MinkParameters         $minkParameters
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $priceListFactory,
        RepositoryInterface $priceListRepository,
        ObjectManager $priceListManager,
        MinkParameters $minkParameters
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->priceListFactory = $priceListFactory;
        $this->priceListRepository = $priceListRepository;
        $this->priceListManager = $priceListManager;
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
        $priceList->setPath($filesPath.$path);
        $priceList->setActive(true);
        $dealer->setPriceList($priceList);

        $this->priceListRepository->add($priceList);
        $this->sharedStorage->set('price_list', $priceList);
    }

    /**
     * @Given /^(this dealer) has no active subscription$/
     */
    public function thisDealerHasNoActiveSubscription(Dealer $dealer)
    {
        $priceList = $dealer->getPriceList();

        if (null === $priceList) {
            return;
        }

        $priceList->setActive(false);
        $this->priceListManager->flush();
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
