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

use App\Entity\GamePlay;
use App\Entity\Player;
use App\Fixture\OptionsResolver\LazyOption;
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PlayerExampleFactory extends AbstractExampleFactory
{
    /**
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $playerFactory,
        private RepositoryInterface $gamePlayRepository
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

        /** @var Player $player */
        $player = $this->playerFactory->createNew();
        $player->setName($options['name']);
        $player->setGamePlay($options['game_play']);
        $player->setScore($options['score']);

        return $player;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', function (Options $options) {
                return $this->faker->userName;
            })

            ->setDefault('score', function (Options $options) {
                return $this->faker->numberBetween(10, 300);
            })

            ->setDefault('game_play', LazyOption::randomOne($this->gamePlayRepository))
            ->setAllowedTypes('game_play', ['string', GamePlay::class])
            ->setNormalizer('game_play', LazyOption::findOneBy($this->gamePlayRepository, 'code'));
    }
}
