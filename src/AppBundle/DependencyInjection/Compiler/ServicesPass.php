<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->processFactories($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processFactories(ContainerBuilder $container)
    {
        $topicFactoryDefinition = $container->getDefinition('app.factory.topic');
        $topicFactoryDefinition
            ->addMethodCall('setGamePlayRepository', [new Reference('app.repository.game_play')]);

        $notificationFactoryDefinition = $container->getDefinition('app.factory.notification');
        $notificationFactoryDefinition
            ->addMethodCall('setRouter', [new Reference('router')])
            ->addMethodCall('setTranslator', [new Reference('translator')]);

        $gamePlayFactoryDefinition = $container->getDefinition('app.factory.game_play');
        $gamePlayFactoryDefinition
            ->addMethodCall('setProductRepository', [new Reference('sylius.repository.product')])
            ->addMethodCall('setCustomerContext', [new Reference('sylius.context.customer')]);

        $articleContentFactoryDefinition = $container->getDefinition('app.factory.article_content');
        $articleContentFactoryDefinition
            ->addMethodCall('setDocumentManager', [new Reference('app.manager.article_content')]);

        $articleFactoryDefinition = $container->getDefinition('app.factory.article');
        $articleFactoryDefinition
            ->addMethodCall('setArticleContentFactory', [new Reference('app.factory.article_content')])
            ->addMethodCall('setProductRepository', [new Reference('sylius.factory.product')]);
    }
}
