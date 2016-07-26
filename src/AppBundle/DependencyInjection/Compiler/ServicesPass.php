<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 22/03/2016
 * Time: 08:38
 */

namespace AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author LoÃ¯c FrÃ©mont <loic@mobizel.com>
 */
class ServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $topicFactoryDefinition = $container->getDefinition('app.factory.topic');
        $topicFactoryDefinition
            ->addMethodCall('setGamePlayRepository', [ new Reference('app.repository.game_play') ]);

        $notificationFactoryDefinition = $container->getDefinition('app.factory.notification');
        $notificationFactoryDefinition
            ->addMethodCall('setRouter', [ new Reference('router') ])
            ->addMethodCall('setTranslator', [ new Reference('translator') ]);

        $gamePlayFactoryDefinition = $container->getDefinition('app.factory.game_play');
        $gamePlayFactoryDefinition
            ->addMethodCall('setProductRepository', [ new Reference('sylius.repository.product') ])
            ->addMethodCall('setCustomerContext', [ new Reference('sylius.context.customer') ]);
    }
}