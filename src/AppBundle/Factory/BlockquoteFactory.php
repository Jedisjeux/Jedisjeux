<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Document\BlockquoteBlock;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class BlockquoteFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return object
     */
    public function createNew()
    {
        return new $this->className;
    }

    /**
     * @return BlockquoteBlock
     */
    public function createWithFakeData()
    {
        /** @var BlockquoteBlock $block */
        $block = $this->createNew();

        $faker = \Faker\Factory::create();
        $paragraphs = $faker->paragraphs(2);

        $block->setBody(sprintf('<p>%s</p>', implode('</p><p>', $paragraphs)));

        return $block;
    }
}
