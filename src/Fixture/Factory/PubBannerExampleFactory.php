<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture\Factory;

use App\Entity\PubBanner;
use App\Fixture\OptionsResolver\LazyOption;
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PubBannerExampleFactory extends AbstractExampleFactory
{
    /** @var Generator */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $pubBannerFactory,
        private RepositoryInterface $dealerRepository
    ) {
        $this->faker = Factory::create();
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
    protected function configureOptions(OptionsResolver $resolver): void
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

    private function createImage(PubBanner $pubBanner, array $options): void
    {
        $filesystem = new Filesystem();
        $imagePath = $options['image'];
        $basename = basename($imagePath);
        $filesystem->copy($imagePath, '/tmp/'.$basename);
        $file = new UploadedFile('/tmp/'.$basename, $basename, null, null, true);

        $pubBanner->setFile($file);
    }
}
