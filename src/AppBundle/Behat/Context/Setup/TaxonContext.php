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

use AppBundle\Behat\Service\SharedStorageInterface;
use AppBundle\Fixture\Factory\ExampleFactoryInterface;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class TaxonContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var ExampleFactoryInterface
     */
    protected $taxonFactory;

    /**
     * @var RepositoryInterface
     */
    protected $taxonRepository;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * TaxonContext constructor.
     *
     * @param SharedStorageInterface $sharedStorage
     * @param ExampleFactoryInterface $taxonFactory
     * @param RepositoryInterface $taxonRepository
     * @param EntityManager $manager
     */
    public function __construct(SharedStorageInterface $sharedStorage, ExampleFactoryInterface $taxonFactory, RepositoryInterface $taxonRepository, EntityManager $manager)
    {
        $this->sharedStorage = $sharedStorage;
        $this->taxonFactory = $taxonFactory;
        $this->taxonRepository = $taxonRepository;
        $this->manager = $manager;
    }

    /**
     * @Given /^there is taxon with code "([^"]+)"$/
     */
    public function thereIsTaxonWithCode($code)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->taxonFactory->create(['code' => $code]);
        $this->taxonRepository->add($taxon);

        $this->sharedStorage->set('taxon', $taxon);
    }

    /**
     * @Given /^(this taxon) has children taxon "([^"]+)" and "([^"]+)"$/
     */
    public function thisTaxonHasChildrenTaxonAnd(TaxonInterface $taxon, $firstTaxonName, $secondTaxonName)
    {
        /** @var TaxonInterface $firstTaxon */
        $firstTaxon = $this->taxonFactory->create(['name' => $firstTaxonName]);
        /** @var TaxonInterface $secondTaxon */
        $secondTaxon = $this->taxonFactory->create(['name' => $secondTaxonName]);

        $taxon->addChild($firstTaxon);
        $taxon->addChild($secondTaxon);

        $this->manager->flush($taxon);
    }
}
