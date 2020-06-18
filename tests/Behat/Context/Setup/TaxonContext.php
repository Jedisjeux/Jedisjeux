<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Setup;

use App\Tests\Behat\Service\SharedStorageInterface;
use App\Entity\Taxon;
use App\Fixture\Factory\ExampleFactoryInterface;
use App\Fixture\Factory\TaxonExampleFactory;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Translation\PluralizationRules;

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
     * @var TaxonExampleFactory
     */
    protected $taxonFactory;

    /**
     * @var RepositoryInterface
     */
    protected $taxonRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param TaxonExampleFactory    $taxonFactory
     * @param RepositoryInterface    $taxonRepository
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        TaxonExampleFactory $taxonFactory,
        RepositoryInterface $taxonRepository,
        EntityManagerInterface $manager
    ) {
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
     * @Given /^there is a (mechanism|theme) "([^"]+)"$/
     */
    public function thereIsTaxon($taxonCode, $taxonName)
    {
        /** @var TaxonInterface $taxon */
        $taxon = $this->sharedStorage->get(sprintf('taxonomy_%ss', $taxonCode));

        $this->taxonFactory->create(['name' => $taxonName, 'parent' => $taxon, 'public' => true]);

        $this->manager->flush();
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

        $this->manager->flush();
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

        $this->manager->flush();
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

        $this->manager->flush();
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

        $this->manager->flush();
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
