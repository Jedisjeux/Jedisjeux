<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture\Factory;

use AppBundle\Entity\Taxon;
use AppBundle\Formatter\StringInflector;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kamil Kokot <kamil@kokot.me>
 */
class TaxonExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $taxonFactory;

    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var TaxonSlugGeneratorInterface
     */
    private $taxonSlugGenerator;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $taxonFactory
     * @param TaxonRepositoryInterface $taxonRepository
     * @param TaxonSlugGeneratorInterface $taxonSlugGenerator
     */
    public function __construct(
        FactoryInterface $taxonFactory,
        TaxonRepositoryInterface $taxonRepository,
        TaxonSlugGeneratorInterface $taxonSlugGenerator
    ) {
        $this->taxonFactory = $taxonFactory;
        $this->taxonRepository = $taxonRepository;
        $this->taxonSlugGenerator = $taxonSlugGenerator;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Taxon $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $options['code']]);

        if (null === $taxon) {
            $taxon = $this->taxonFactory->createNew();
        }

        $taxon->setCode($options['code']);

        foreach ($this->getLocales() as $localeCode) {
            $taxon->setCurrentLocale($localeCode);
            $taxon->setFallbackLocale($localeCode);

            $taxon->setName($options['name']);
            $taxon->setDescription($options['description']);
            $taxon->setSlug($options['slug'] ?: $this->taxonSlugGenerator->generate($taxon, $localeCode));
        }

        foreach ($options['children'] as $childOptions) {
            $taxon->addChild($this->create($childOptions));
        }

        return $taxon;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', function (Options $options) {
                return $this->faker->words(3, true);
            })
            ->setDefault('code', function (Options $options) {
                return StringInflector::nameToCode($options['name']);
            })
            ->setDefault('slug', null)
            ->setDefault('description', function (Options $options) {
                return $this->faker->paragraph;
            })
            ->setDefault('children', [])
            ->setAllowedTypes('children', ['array'])
        ;
    }

    /**
     * @return array
     */
    private function getLocales()
    {
        return [
            'fr_FR',
        ];
    }
}
