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

use AppBundle\Entity\Block;
use AppBundle\Entity\BlockImage;
use Sylius\Component\Resource\Factory\FactoryInterface;
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
     */
    public function __construct(
        FactoryInterface $blockFactory,
        FactoryInterface $blockImageFactory
    )
    {
        $this->blockFactory = $blockFactory;
        $this->blockImageFactory = $blockImageFactory;

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

        $this->createImage($block, $options);

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
                return "<p>" . implode("</p><p>", $this->faker->paragraphs()) . '</p>';
            })
            ->setDefault('image', function (Options $options) {
                return $this->faker->image();
            });
    }
}
