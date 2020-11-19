<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixture;

use App\Fixture\Factory\FestivalListExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class FestivalListFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, FestivalListExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'festival_list';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->end()
                ->scalarNode('description')->end()
                ->scalarNode('start_at')->end()
                ->scalarNode('end_at')->end()
        ;
    }
}
