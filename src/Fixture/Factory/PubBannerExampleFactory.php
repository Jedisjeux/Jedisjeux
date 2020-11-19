<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture\Factory;

use App\Entity\PubBanner;
use App\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PubBannerExampleFactory extends AbstractExampleFactory
{
    /** @var FactoryInterface */
    private $pubBannerFactory;

    /** @var RepositoryInterface */
    private $dealerRepository;

    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    public function __construct(FactoryInterface $pubBannerFactory, RepositoryInterface $dealerRepository)
    {
        $this->pubBannerFactory = $pubBannerFactory;
        $this->dealerRepository = $dealerRepository;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options = []): PubBanner
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var PubBanner $pubBanner */
        $pubBanner = $this->pubBannerFactory->createNew();
        $pubBanner->setTargetUrl($options['target_url']);
        $pubBanner->setDealer($options['dealer']);
        $this->createImage($pubBanner, $options);

        return $pubBanner;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('target_url', function (Options $options) {
                return $this->faker->url;
            })
            ->setDefault('dealer', LazyOption::randomOne($this->dealerRepository))
            ->setDefault('image', LazyOption::randomOneImage(
                __DIR__.'/../../../tests/Resources/fixtures/pub_banners'
            ))
        ;
    }

    private function createImage(PubBanner $pubBanner, array $options)
    {
        $filesystem = new Filesystem();
        $imagePath = $options['image'];
        $basename = basename($imagePath);
        $filesystem->copy($imagePath, '/tmp/'.$basename);
        $file = new UploadedFile('/tmp/'.$basename, $basename, null, null, true);

        $pubBanner->setFile($file);
    }
}
