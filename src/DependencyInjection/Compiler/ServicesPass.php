<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DependencyInjection\Compiler;

use App\EventListener\PasswordUpdaterListener;
use App\Factory\ProductFactory;
use App\Security\Authorization\AuthorizationChecker;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->processFactories($container);
        $this->processFormTypes($container);
        $this->processListeners($container);

        $container->setAlias('sylius.context.customer', 'app.context.customer')->setPublic(true);

        $contextLocaleCompositeDefinition = $container->getDefinition('sylius.context.locale.composite');
        $contextLocaleCompositeDefinition->setDecoratedService(null);

        $authorizationCheckerDefinition = $container->getDefinition('security.authorization_checker');
        $authorizationCheckerDefinition->setClass(AuthorizationChecker::class);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processFactories(ContainerBuilder $container)
    {
        $topicFactoryDefinition = $container->getDefinition('app.factory.topic');
        $topicFactoryDefinition
            ->addArgument(new Reference('app.context.customer'))
            ->addArgument(new Reference('app.repository.game_play'))
            ->addArgument(new Reference('app.factory.post'));

        $postFactoryDefinition = $container->getDefinition('app.factory.post');
        $postFactoryDefinition
            ->addArgument(new Reference('app.context.customer'));

        $notificationFactoryDefinition = $container->getDefinition('app.factory.notification');
        $notificationFactoryDefinition
            ->addMethodCall('setRouter', [new Reference('router')])
            ->addMethodCall('setTranslator', [new Reference('translator.default')]);

        $gamePlayFactoryDefinition = $container->getDefinition('app.factory.game_play');
        $gamePlayFactoryDefinition
            ->addArgument(new Reference('sylius.repository.product'))
            ->addArgument(new Reference('app.context.customer'));

        $productFactoryDefinition = $container->getDefinition('sylius.custom_factory.product');
        $productFactoryDefinition
            ->setClass(ProductFactory::class)
            ->addArgument(new Reference('app.factory.product_image'))
            ->addArgument(new Reference('app.factory.bgg_product'))
            ->addArgument(new Reference('sylius.generator.slug'))
            ->addArgument(new Reference('app.repository.person'));

        $articleFactoryDefinition = $container->getDefinition('app.factory.article');
        $articleFactoryDefinition
            ->addArgument(new Reference('sylius.repository.product'))
            ->addArgument(new Reference('app.context.customer'))
            ->addArgument(new Reference('app.factory.block'));

        $contactRequestFactoryDefinition = $container->getDefinition('app.factory.contact_request');
        $contactRequestFactoryDefinition
            ->addArgument(new Reference('app.context.customer'));

        $productListFactoryDefinition = $container->getDefinition('app.factory.product_list');
        $productListFactoryDefinition
            ->addArgument(new Reference('app.context.customer'))
            ->addArgument(new Reference('translator.default'));
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processFormTypes(ContainerBuilder $container)
    {
        $articleFormTypeDefinition = $container->getDefinition('app.form.type.article');
        $articleFormTypeDefinition
            ->addMethodCall('setManager', [new Reference('doctrine.orm.entity_manager')]);

        $gamePlayFormTypeDefinition = $container->getDefinition('app.form.type.game_play');
        $gamePlayFormTypeDefinition
            ->addMethodCall('setManager', [new Reference('doctrine.orm.entity_manager')]);

        $topicFormTypeDefinition = $container->getDefinition('app.form.type.topic');
        $topicFormTypeDefinition
            ->addMethodCall('setAuthorizationChecker', [new Reference('security.authorization_checker')]);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function processListeners(ContainerBuilder $container)
    {
        $listenerPasswordUpdaterDefinition = $container->getDefinition('sylius.listener.password_updater');
        $listenerPasswordUpdaterDefinition
            ->setClass(PasswordUpdaterListener::class);
        $listenerPasswordUpdaterDefinition->addTag('kernel.event_listener', [
            'event' => 'sylius.customer.pre_update',
            'method' => 'customerUpdateEvent',
        ]);
    }
}
