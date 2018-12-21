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
use App\Entity\YearAward;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YearAwardExampleFactory extends AbstractExampleFactory
{
    /**
     * @var FactoryInterface
     */
    protected $yearAwardFactory;

    /**
     * @var RepositoryInterface
     */
    protected $gameAwardRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface    $yearAwardFactory
     * @param RepositoryInterface $gameAwardRepository
     */
    public function __construct(FactoryInterface $yearAwardFactory, RepositoryInterface $gameAwardRepository)
    {
        $this->yearAwardFactory = $yearAwardFactory;
        $this->gameAwardRepository = $gameAwardRepository;

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
            ->setDefault('year', function (Options $options) {
                return $this->faker->year;
            })

            ->setDefault('award', LazyOption::randomOne($this->gameAwardRepository))
            ->setAllowedTypes('award', ['null', 'string', GameAward::class])
            ->setNormalizer('award', LazyOption::findOneBy($this->gameAwardRepository, 'slug'));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var YearAward $yearAward */
        $yearAward = $this->yearAwardFactory->createNew();
        $yearAward->setYear($options['year']);
        $yearAward->setAward($options['award']);

        return $yearAward;
    }
}
