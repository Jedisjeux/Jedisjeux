<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\GameAward;
use App\Entity\GameAwardImage;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameAwardExampleFactory extends AbstractExampleFactory
{
    /**
     * @var FactoryInterface
     */
    private $gameAwardFactory;

    /**
     * @var FactoryInterface
     */
    private $gameAwardImageFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $gameAwardFactory
     * @param FactoryInterface $gameAwardImageFactory
     */
    public function __construct(FactoryInterface $gameAwardFactory, FactoryInterface $gameAwardImageFactory)
    {
        $this->gameAwardFactory = $gameAwardFactory;
        $this->gameAwardImageFactory = $gameAwardImageFactory;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
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

            ->setDefault('image', LazyOption::randomOneImageOrNull(
                __DIR__.'/../../../tests/Resources/fixtures/awards', 80
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var GameAward $gameAward */
        $gameAward = $this->gameAwardFactory->createNew();
        $gameAward->setName($options['name']);

        if (null !== $options['image']) {
            $this->createImage($gameAward, $options);
        }

        return $gameAward;
    }

    /**
     * @param GameAward $gameAward
     * @param array  $options
     */
    private function createImage(GameAward $gameAward, array $options)
    {
        $imagePath = $options['image'];
        /** @var GameAwardImage $image */
        $image = $this->gameAwardImageFactory->createNew();
        $image->setPath(basename($imagePath));

        file_put_contents($image->getAbsolutePath(), file_get_contents($imagePath));

        $gameAward->setImage($image);
    }
}
