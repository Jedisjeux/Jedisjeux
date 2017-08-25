<?php

/*
 * This file is part of BackEdt.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
final class ProductMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $options
     *
     * @return ItemInterface
     */
    public function createMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu
            ->addChild('details')
            ->setAttribute('template', 'backend/product/tab/_details.html.twig')
            ->setLabel('sylius.ui.details')
            ->setCurrent(true)
        ;

        $menu
            ->addChild('taxonomy')
            ->setAttribute('template', 'backend/product/tab/_taxonomy.html.twig')
            ->setLabel('sylius.ui.taxonomy')
        ;

        $menu
            ->addChild('attributes')
            ->setAttribute('template', 'backend/product/tab/_attributes.html.twig')
            ->setLabel('sylius.ui.attributes')
        ;

        $menu
            ->addChild('people')
            ->setAttribute('template', 'backend/product/tab/_people.html.twig')
            ->setLabel('app.ui.people')
        ;

        $menu
            ->addChild('associations')
            ->setAttribute('template', 'backend/product/tab/_associations.html.twig')
            ->setLabel('sylius.ui.associations')
        ;

        $menu
            ->addChild('media')
            ->setAttribute('template', 'backend/product/tab/_media.html.twig')
            ->setLabel('sylius.ui.media')
        ;

        $menu
            ->addChild('barcodes')
            ->setAttribute('template', 'backend/product/tab/_barcodes.html.twig')
            ->setLabel('app.ui.barcodes')
        ;

        return $menu;
    }
}
