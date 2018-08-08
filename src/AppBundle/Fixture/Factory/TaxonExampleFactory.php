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
use AppBundle\Fixture\OptionsResolver\LazyOption;
use AppBundle\Formatter\StringInflector;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
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
     * @var string
     */
    private $localeCode;

    /**
     * @param FactoryInterface $taxonFactory
     * @param TaxonRepositoryInterface $taxonRepository
     * @param TaxonSlugGeneratorInterface $taxonSlugGenerator
     * @param string $localeCode
     */
    public function __construct(
        FactoryInterface $taxonFactory,
        TaxonRepositoryInterface $taxonRepository,
        TaxonSlugGeneratorInterface $taxonSlugGenerator,
        $localeCode
    )
    {
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

        $taxon->setCurrentLocale($this->localeCode);
        $taxon->setFallbackLocale($this->localeCode);

        $taxon->setName($options['name']);
        $taxon->setDescription($options['description']);
        $taxon->setPublic($options['public']);
        $taxon->setIconClass($options['icon_class']);
        $taxon->setColor($options['color']);

        $taxon->setParent($options['parent']);

        foreach ($options['children'] as $childOptions) {
            $taxon->addChild($this->create($childOptions));
        }

        $taxon->setSlug($options['slug'] ?: $this->taxonSlugGenerator->generate($taxon, $this->localeCode));

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
            ->setAllowedTypes('children', 'array');
    }
}
