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
use AppBundle\Entity\Taxon;
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
     * @Given there are default taxonomies for products
     */
    public function thereAreDefaultTaxonomiesForProducts()
    {
        $taxonCodes = [
            Taxon::CODE_MECHANISM,
            Taxon::CODE_THEME,
            Taxon::CODE_TARGET_AUDIENCE,
        ];

        $this->createDefaultTaxonomies($taxonCodes);
    }

    /**
     * @Given there are default taxonomies for people
     */
    public function thereAreDefaultTaxonomiesForPeople()
    {
        $taxonCodes = [
            Taxon::CODE_ZONE,
        ];

        $this->createDefaultTaxonomies($taxonCodes);
    }

    /**
     * @Given there are default taxonomies for articles
     */
    public function thereAreDefaultTaxonomiesForArticles()
    {
        $taxonCodes = [
            Taxon::CODE_ARTICLE,
        ];

        $this->createDefaultTaxonomies($taxonCodes);
    }

    /**
     * @Given there are default taxonomies for topics
     */
    public function thereAreDefaultTaxonomiesForTopics()
    {
        $taxonCodes = [
            Taxon::CODE_FORUM,
        ];

        $this->createDefaultTaxonomies($taxonCodes);
    }

    /**
     * @Given /^there are (mechanisms|themes) "([^"]+)" and "([^"]+)"$/
     */
    public function thereAreTaxonsAnd($taxonCode, $firstTaxonName, $secondTaxonName)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get(sprintf('taxonomy_%s', $taxonCode));

        $this->taxonFactory->create(['name' => $firstTaxonName, 'parent' => $taxon, 'public' => true]);
        $this->taxonFactory->create(['name' => $secondTaxonName, 'parent' => $taxon, 'public' => true]);

        $this->manager->flush($taxon);
    }

    /**
     * @Given /^there are article categories "([^"]+)" and "([^"]+)"$/
     */
    public function thereAreArticleCategoriesAnd($firstTaxonName, $secondTaxonName)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get('taxonomy_articles');

        $this->taxonFactory->create(['name' => $firstTaxonName, 'parent' => $taxon]);
        $this->taxonFactory->create(['name' => $secondTaxonName, 'parent' => $taxon]);

        $this->manager->flush($taxon);
    }

    /**
     * @Given /^there are topic categories "([^"]+)" and "([^"]+)"$/
     */
    public function thereAreTopicCategoriesAnd($firstTaxonName, $secondTaxonName)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get('taxonomy_forum');

        $this->taxonFactory->create(['name' => $firstTaxonName, 'parent' => $taxon, 'public' => true]);
        $this->taxonFactory->create(['name' => $secondTaxonName, 'parent' => $taxon, 'public' => true]);

        $this->manager->flush($taxon);
    }

    /**
     * @Given /^there is a (private|public) topic category "([^"]+)"$/
     */
    public function thereArePrivateOrPublicTopicCategory($visibility, $name)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get('taxonomy_forum');

        $public = 'public' === $visibility;

        $this->taxonFactory->create(['name' => $name, 'parent' => $taxon, 'public' => $public]);

        $this->manager->flush($taxon);
    }

    /**
     * @param array $codes
     */
    protected function createDefaultTaxonomies(array $codes)
    {
        foreach ($codes as $code) {
            /** @var TaxonInterface $taxon */
            $taxon = $this->taxonFactory->create(['code' => $code, 'slug' => $code, 'public' => true]);
            $this->taxonRepository->add($taxon);

            $this->sharedStorage->set(sprintf('taxonomy_%s', $code), $taxon);
        }
    }
}
