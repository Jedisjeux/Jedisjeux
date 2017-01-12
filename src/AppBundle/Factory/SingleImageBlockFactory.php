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

use AppBundle\Document\ImagineBlock;
use AppBundle\Document\SingleImageBlock;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class SingleImageBlockFactory implements FactoryInterface
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
     * @return SingleImageBlock
     */
    public function createNew()
    {
        /** @var SingleImageBlock $singleImageBlock */
        $singleImageBlock = new $this->className;

        return $singleImageBlock;
    }

    /**
     * @return SingleImageBlock
     */
    public function createWithFakeBody()
    {
        /** @var SingleImageBlock $block */
        $block = $this->createNew();

        $faker = \Faker\Factory::create();
        $paragraphs = $faker->paragraphs(3);

        $block->setBody(sprintf('<p>%s</p>', implode('</p><p>', $paragraphs)));

        return $block;
    }
}
