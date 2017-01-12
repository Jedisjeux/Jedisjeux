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
            ->addMethodCall('setTranslator', [new Reference('translator')]);

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
            ->addMethodCall('setLeftImageBlockFactory', [new Reference('app.factory.left_image_block')])
            ->addMethodCall('setRightImageBlockFactory', [new Reference('app.factory.right_image_block')])
            ->addMethodCall('setWellImageBlockFactory', [new Reference('app.factory.well_image_block')])
            ->addMethodCall('setBlockquoteBlockFactory', [new Reference('app.factory.blockquote_block')]);

        $contactRequestFactoryDefinition = $container->getDefinition('app.factory.contact_request');
        $contactRequestFactoryDefinition
            ->addMethodCall('setCustomerContext', [new Reference('app.context.customer')]);

        $stringBlockFactoryDefinition = $container->getDefinition('app.factory.string_block');
        $stringBlockFactoryDefinition
            ->addArgument(new Reference('doctrine_phpcr.odm.document_manager'))
            ->addArgument(new Parameter('cmf_block.persistence.phpcr.block_basepath'));

        $productListFactoryDefinition = $container->getDefinition('app.factory.product_list');
        $productListFactoryDefinition
            ->addArgument(new Reference('app.context.customer'));
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
    }
}
