<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture\Factory;

use App\Entity\Article;
use App\Entity\Block;
use App\Entity\BlockImage;
use App\Fixture\OptionsResolver\LazyOption;
use Faker\Factory;
use Faker\Generator;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class BlockExampleFactory extends AbstractExampleFactory implements ExampleFactoryInterface
{
    /**
     * @var Generator
     */
    private $faker;

    private OptionsResolver $optionsResolver;

    public function __construct(
        private FactoryInterface $blockFactory,
        private FactoryInterface $blockImageFactory,
        private RepositoryInterface $articleRepository
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

        /** @var Block $block */
        $block = $this->blockFactory->createNew();
        $block->setTitle($options['title']);
        $block->setBody($options['body']);
        $block->setImagePosition($options['image_position']);
        $block->setArticle($options['article']);

        if (null !== $options['image']) {
            $this->createImage($block, $options);
        }

        return $block;
    }

    private function createImage(Block $block, array $options): void
    {
        $imagePath = $options['image'];
        /** @var BlockImage $blockImage */
        $blockImage = $this->blockImageFactory->createNew();
        $blockImage->setPath(basename($imagePath));
        $blockImage->setLabel($options['image_label']);

        file_put_contents($blockImage->getAbsolutePath(), file_get_contents($imagePath));

        $block->setImage($blockImage);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('title', null)

            ->setDefault('body', function (Options $options): string {
                return '<p>'.implode('</p><p>', $this->faker->paragraphs(5)).'</p>';
            })

            ->setDefault('image', LazyOption::randomOneImage(
                __DIR__.'/../../../tests/Resources/fixtures/blocks'
            ))

            ->setDefault('image_position', function (Options $options) {
                return $this->faker->randomElement([Block::POSITION_TOP, Block::POSITION_LEFT, Block::POSITION_RIGHT]);
            })

            ->setDefault('image_label', function (Options $options) {
                return $this->faker->sentence();
            })

            ->setDefault('article', LazyOption::randomOne($this->articleRepository))
            ->setAllowedTypes('article', ['null', 'string', Article::class])
            ->setNormalizer('article', LazyOption::findOneBy($this->articleRepository, 'code'));
    }
}
