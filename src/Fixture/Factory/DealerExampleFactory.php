<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\Dealer;
use App\Entity\DealerImage;
use App\Fixture\OptionsResolver\LazyOption;
use App\Formatter\StringInflector;
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

        if (null !== $options['image']) {
            $this->createImage($dealer, $options);
        }

        return $dealer;
    }

    /**
     * @param Dealer $dealer
     * @param array  $options
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

            ->setDefault('image', LazyOption::randomOneImageOrNull(
                __DIR__.'/../../../tests/Resources/fixtures/dealers', 80
            ));
    }
}
