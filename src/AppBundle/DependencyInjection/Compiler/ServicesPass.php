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

use AppBundle\EventListener\PasswordUpdaterListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Parameter;
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
        $this->processFormTypes($container);
        $this->processControllers($container);
        $this->processListeners($container);

        $container->setAlias('sylius.context.customer', 'app.context.customer');
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processFactories(ContainerBuilder $container)
    {
        $topicFactoryDefinition = $container->getDefinition('app.factory.topic');
        $topicFactoryDefinition
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')])
            ->addMethodCall('setGamePlayRepository', [new Reference('app.repository.game_play')])
            ->addMethodCall('setPostFactory', [new Reference('app.factory.post')]);

        $postFactoryDefinition = $container->getDefinition('app.factory.post');
        $postFactoryDefinition
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')]);

        $notificationFactoryDefinition = $container->getDefinition('app.factory.notification');
        $notificationFactoryDefinition
            ->addMethodCall('setRouter', [new Reference('router')])
            ->addMethodCall('setTranslator', [new Reference('translator.default')]);

        $gamePlayFactoryDefinition = $container->getDefinition('app.factory.game_play');
        $gamePlayFactoryDefinition
            ->addMethodCall('setProductRepository', [new Reference('sylius.repository.product')])
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')]);

        $articleContentFactoryDefinition = $container->getDefinition('app.factory.article_content');
        $articleContentFactoryDefinition
            ->addMethodCall('setDocumentManager', [new Reference('app.manager.article_content')]);

        $articleFactoryDefinition = $container->getDefinition('app.factory.article');
        $articleFactoryDefinition
            ->addMethodCall('setArticleContentFactory', [new Reference('app.factory.article_content')])
            ->addMethodCall('setProductRepository', [new Reference('sylius.repository.product')])
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')])
            ->addMethodCall('setBlockFactory', [new Reference('app.factory.block')]);

        $contactRequestFactoryDefinition = $container->getDefinition('app.factory.contact_request');
        $contactRequestFactoryDefinition
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')]);

        $stringBlockFactoryDefinition = $container->getDefinition('app.factory.string_block');
        $stringBlockFactoryDefinition
            ->addArgument(new Reference('doctrine_phpcr.odm.document_manager'))
            ->addArgument(new Parameter('cmf_block.persistence.phpcr.block_basepath'));

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
        $dealerFormTypeDefinition = $container->getDefinition('app.form.type.dealer');
        $dealerFormTypeDefinition
            ->addMethodCall('setManager', [new Reference('app.manager.dealer')]);

        $topicFormTypeDefinition = $container->getDefinition('app.form.type.topic');
        $topicFormTypeDefinition
            ->addMethodCall('setAuthorizationChecker', [new Reference('security.authorization_checker')]);

        $customerFormTypeDefinition = $container->getDefinition('sylius.form.type.customer');
        $customerFormTypeDefinition
            ->addArgument(new Reference('sylius.form.event_subscriber.add_shop_user_type'));
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function processControllers(ContainerBuilder $container)
    {
        $resourcesCollectionProviderDefinition = $container->getDefinition('sylius.resource_controller.resources_collection_provider');
        $resourcesCollectionProviderDefinition
            ->setClass('AppBundle\Controller\ResourcesCollectionProvider');
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
            'method' => 'customerUpdateEvent'
        ]);
    }
}
