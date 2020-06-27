<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture;

use App\Fixture\Factory\NotificationExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class NotificationFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, NotificationExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->booleanNode('read')->end()
                ->scalarNode('message')->cannotBeEmpty()->end()
                ->scalarNode('target')->cannotBeEmpty()->end()
                ->scalarNode('recipient')->cannotBeEmpty()->end()
                ->arrayNode('authors')->prototype('scalar')->end()->end()
        ;
    }
}
