<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Fixture\Factory;

use AppBundle\Entity\Article;
use AppBundle\Entity\Block;
use AppBundle\Entity\BlockImage;
use AppBundle\Fixture\OptionsResolver\LazyOption;
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
     * @var FactoryInterface
     */
    private $blockFactory;

    /**
     * @var FactoryInterface
     */
    private $blockImageFactory;

    /**
     * @var RepositoryInterface
     */
    private $articleRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param FactoryInterface $blockFactory
     * @param FactoryInterface $blockImageFactory
     * @param RepositoryInterface $articleRepository
     */
    public function __construct(
        FactoryInterface $blockFactory,
        FactoryInterface $blockImageFactory,
        RepositoryInterface $articleRepository
    )
    {
        $this->blockFactory = $blockFactory;
        $this->blockImageFactory = $blockImageFactory;
        $this->articleRepository = $articleRepository;

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

    /**
     * @param Block $block
     * @param array $options
     */
    private function createImage(Block $block, array $options)
    {
        $imagePath = $options['image'];
        /** @var BlockImage $blockImage */
        $blockImage = $this->blockImageFactory->createNew();
        $blockImage->setPath(basename($imagePath));

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

            ->setDefault('body', function (Options $options) {
                return "<p>" . implode("</p><p>", $this->faker->paragraphs(5)) . '</p>';
            })

            ->setDefault('image', function (Options $options) {
                $image = $this->faker->image();

                if (!$image) {
                    return 'https://picsum.photos/640/480/?random';
                }

                return $image;
            })

            ->setDefault('image_position', function (Options $options) {
                return $this->faker->randomElement([Block::POSITION_TOP, Block::POSITION_LEFT, Block::POSITION_RIGHT]);
            })

            ->setDefault('article', LazyOption::randomOne($this->articleRepository))
            ->setAllowedTypes('article', ['null', 'string', Article::class])
            ->setNormalizer('article', LazyOption::findOneBy($this->articleRepository, 'code'));
    }
}
