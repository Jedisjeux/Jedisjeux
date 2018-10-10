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
class GamePlayFixture extends AbstractResourceFixture
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'game_play';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('duration')->cannotBeEmpty()->end()
                ->scalarNode('player_count')->cannotBeEmpty()->end()
                ->scalarNode('played_at')->cannotBeEmpty()->end()
                ->scalarNode('product')->cannotBeEmpty()->end()
                ->scalarNode('author')->cannotBeEmpty()->end()
        ;
    }
}
