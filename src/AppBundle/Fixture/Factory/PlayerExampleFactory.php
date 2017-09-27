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
use AppBundle\Entity\Player;
use AppBundle\Fixture\OptionsResolver\LazyOption;
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
     * @var FactoryInterface
     */
    private $playerFactory;

    /**
     * @var RepositoryInterface
     */
    private $gamePlayRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $playerFactory
     * @param RepositoryInterface $gamePlayRepository
     */
    public function __construct(
        FactoryInterface $playerFactory,
        RepositoryInterface $gamePlayRepository
    )
    {
        $this->playerFactory = $playerFactory;
        $this->gamePlayRepository = $gamePlayRepository;

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
