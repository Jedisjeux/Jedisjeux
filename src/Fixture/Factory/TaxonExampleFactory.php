<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture\Factory;

use App\Entity\Taxon;
use App\Fixture\OptionsResolver\LazyOption;
use App\Formatter\StringInflector;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @var string
     */
    private $localeCode;

    /**
     * @param FactoryInterface            $taxonFactory
     * @param TaxonRepositoryInterface    $taxonRepository
     * @param TaxonSlugGeneratorInterface $taxonSlugGenerator
     * @param string                      $localeCode
     */
    public function __construct(
        FactoryInterface $taxonFactory,
        TaxonRepositoryInterface $taxonRepository,
        TaxonSlugGeneratorInterface $taxonSlugGenerator,
        $localeCode
    ) {
        $this->taxonFactory = $taxonFactory;
        $this->taxonRepository = $taxonRepository;
        $this->taxonSlugGenerator = $taxonSlugGenerator;
        $this->localeCode = $localeCode;

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
        $taxon->setPublic($options['public']);
        $taxon->setIconClass($options['icon_class']);
        $taxon->setColor($options['color']);
        $taxon->setParent($options['parent']);

        // add translation for each defined locales
        foreach ($this->getLocales() as $localeCode) {
            $this->createTranslation($taxon, $localeCode, $options);
        }

        // create or replace with custom translations
        foreach ($options['translations'] as $localeCode => $translationOptions) {
            $this->createTranslation($taxon, $localeCode, $translationOptions);
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

            ->setDefault('public', function (Options $options) {
                return $this->faker->boolean(90);
            })
            ->setAllowedTypes('public', 'bool')

            ->setDefault('icon_class', null)

            ->setDefault('color', null)

            ->setDefault('parent', null)
            ->setAllowedTypes('parent', ['null', 'string', TaxonInterface::class])
            ->setNormalizer('parent', LazyOption::findOneBy($this->taxonRepository, 'code'))

            ->setDefault('children', [])
            ->setAllowedTypes('children', 'array')

            ->setDefault('translations', [])
            ->setAllowedTypes('translations', ['array'])
            ->setDefault('children', [])
            ->setAllowedTypes('children', ['array']);
    }

    /**
     * @param Taxon  $taxon
     * @param string $localeCode
     * @param array  $options
     */
    private function createTranslation(Taxon $taxon, string $localeCode, array $options = []): void
    {
        $options = $this->optionsResolver->resolve($options);

        $taxon->setCurrentLocale($localeCode);
        $taxon->setFallbackLocale($localeCode);

        $taxon->setName($options['name']);
        $taxon->setDescription($options['description']);
        $taxon->setSlug($options['slug'] ?: $this->taxonSlugGenerator->generate($taxon, $localeCode));
    }

    /**
     * @return iterable
     */
    private function getLocales(): iterable
    {
        yield 'fr_FR';
        yield 'en_US';
    }
}
