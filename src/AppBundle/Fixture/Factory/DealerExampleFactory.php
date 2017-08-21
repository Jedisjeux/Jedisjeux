<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture\Factory;

use AppBundle\Entity\Dealer;
use AppBundle\Entity\DealerImage;
use AppBundle\Formatter\StringInflector;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    protected $dealerFactory;

    /**
     * @var FactoryInterface
     */
    protected $dealerImageFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * DealerExampleFactory constructor.
     *
     * @param FactoryInterface $dealerFactory
     * @param FactoryInterface $dealerImageFactory
     */
    public function __construct(FactoryInterface $dealerFactory, FactoryInterface $dealerImageFactory)
    {
        $this->dealerFactory = $dealerFactory;
        $this->dealerImageFactory = $dealerImageFactory;

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

        /** @var Dealer $dealer */
        $dealer = $this->dealerFactory->createNew();
        $dealer->setCode($options['code']);
        $dealer->setName($options['name']);

        $this->createImage($dealer, $options);

        return $dealer;
    }

    /**
     * @param Dealer $dealer
     * @param array $options
     */
    private function createImage(Dealer $dealer, array $options)
    {
        $imagePath = $options['image'];
        /** @var DealerImage $dealerImage */
        $dealerImage = $this->dealerImageFactory->createNew();
        $dealerImage->setPath(basename($imagePath));

        file_put_contents($dealerImage->getAbsolutePath(), file_get_contents($imagePath));

        $dealer->setImage($dealerImage);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', function (Options $options) {
                return ucfirst($this->faker->words(3, true));
            })

            ->setDefault('code', function (Options $options) {
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('image', function (Options $options) {
                return $this->faker->image();
            });
    }
}
