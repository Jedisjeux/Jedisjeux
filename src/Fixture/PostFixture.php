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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PostFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('body')->cannotBeEmpty()->end()
                ->scalarNode('author')->cannotBeEmpty()->end()
        ;
    }
}
