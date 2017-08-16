<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Setup;

use AppBundle\Entity\GamePlay;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    protected $gamePlayFactory;

    /**
     * @var EntityRepository
     */
    protected $gamePlayRepository;

    /**
     * PersonContext constructor.
     *
     * @param ExampleFactoryInterface $gamePlayFactory
     * @param EntityRepository $gamePlayRepository
     */
    public function __construct(ExampleFactoryInterface $gamePlayFactory, EntityRepository $gamePlayRepository)
    {
        $this->gamePlayFactory = $gamePlayFactory;
        $this->gamePlayRepository = $gamePlayRepository;
    }

    /**
     * @Given there is game play of :product played by :customer
     *
     * @param ProductInterface $product
     * @param CustomerInterface $customer
     */
    public function thereIsGamePlayOfProductPlayedByCustomer(ProductInterface $product, CustomerInterface $customer)
    {
        /** @var GamePlay $gamePlay */
        $gamePlay = $this->gamePlayFactory->create([
            'product' => $product,
            'author' => $customer,
        ]);

        $this->gamePlayRepository->add($gamePlay);
    }
}
