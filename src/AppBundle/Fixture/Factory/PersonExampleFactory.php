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

use AppBundle\Entity\Person;
use AppBundle\Entity\PersonImage;
use AppBundle\Entity\ProductVariantImage;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class PersonExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $personFactory;

    /**
     * @var FactoryInterface
     */
    private $personImageFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $personFactory
     * @param FactoryInterface $personImageFactory
     */
    public function __construct(
        FactoryInterface $personFactory,
        FactoryInterface $personImageFactory
    )
    {
        $this->personFactory = $personFactory;
        $this->personImageFactory = $personImageFactory;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var Person $person */
        $person = $this->personFactory->createNew();
        $person->setFirstName($options['first_name']);
        $person->setLastName($options['last_name']);
        $person->setWebsite($options['website']);
        $person->setDescription($options['description']);

        $this->createImages($person, $options);

        return $person;
    }

    /**
     * @param Person $person
     * @param array $options
     */
    private function createImages(Person $person, array $options)
    {
        $first = true;
        
        foreach ($options['images'] as $imagePath) {
            /** @var PersonImage $personImage */
            $personImage = $this->personImageFactory->createNew();
            $personImage->setPath(basename($imagePath));

            if ($first) {
                $personImage->setMain(true);
            }
            
            $first = false;
            
            file_put_contents($personImage->getAbsolutePath(), file_get_contents($imagePath));

            $person->addImage($personImage);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('first_name', function (Options $options) {
                return $this->faker->firstName;
            })
            ->setDefault('last_name', function (Options $options) {
                return $this->faker->lastName;
            })
            ->setDefault('website', function (Options $options) {
                return $this->faker->url;
            })
            ->setDefault('description', function (Options $options) {
                return $this->faker->paragraphs(3, true);
            })
            ->setDefault('images', function (Options $options) {
                return [$this->faker->image(null, 640, 480, 'people')];
            })
            ->setAllowedTypes('images', 'array');
    }
}
