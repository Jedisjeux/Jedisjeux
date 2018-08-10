<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture\Factory;

use AppBundle\Entity\GamePlay;
use AppBundle\Entity\GamePlayImage;
use AppBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class GamePlayImageExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    protected $gamePlayImageFactory;

    /**
     * @var RepositoryInterface
     */
    protected $gamePlayRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * GamePlayExampleFactory constructor.
     *
     * @param FactoryInterface $gamePlayImageFactory
     * @param RepositoryInterface $gamePlayRepository
     */
    public function __construct(
        FactoryInterface $gamePlayImageFactory,
        RepositoryInterface $gamePlayRepository
    )
    {
        $this->gamePlayImageFactory = $gamePlayImageFactory;
        $this->gamePlayRepository = $gamePlayRepository;

        $this->faker = \Faker\Factory::create('fr_FR');
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('image', function (Options $options) {
                $image = $this->faker->image();

                if (!$image) {
                    return 'https://picsum.photos/640/480/?random';
                }

                return $image;
            })

            ->setDefault('description', function (Options $options) {
                return $this->faker->sentence();
            })

            ->setDefault('game_play', LazyOption::randomOne($this->gamePlayRepository))
            ->setAllowedTypes('game_play', ['string', GamePlay::class])
            ->setNormalizer('game_play', LazyOption::findOneBy($this->gamePlayRepository, 'code'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var GamePlayImage $gamePlayImage */
        $gamePlayImage = $this->gamePlayImageFactory->createNew();
        $gamePlayImage->setDescription($options['description']);
        $gamePlayImage->setGamePlay($options['game_play']);

        $this->createImage($gamePlayImage, $options);

        return $gamePlayImage;
    }

    /**
     * @param GamePlayImage $gamePlayImage
     * @param array $options
     */
    private function createImage(GamePlayImage $gamePlayImage, array $options)
    {
        $imagePath = $options['image'];
        $gamePlayImage->setPath(basename($imagePath));

        file_put_contents($gamePlayImage->getAbsolutePath(), file_get_contents($imagePath));
    }
}
