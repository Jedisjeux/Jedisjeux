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
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DealerExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    /**
     * DealerExampleFactory constructor.
     */
    public function __construct(
        private FactoryInterface $dealerFactory,
        private FactoryInterface $dealerImageFactory
    ) {
        $this->faker = Factory::create('fr_FR');
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

    private function createImage(Dealer $dealer, array $options): void
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
            ->setDefault('name', function (Options $options): string {
                return ucfirst($this->faker->words(3, true));
            })

            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode($options['name']);
            })

            ->setDefault('image', LazyOption::randomOneImageOrNull(
                __DIR__.'/../../../tests/Resources/fixtures/dealers', 80
            ));
    }
}
