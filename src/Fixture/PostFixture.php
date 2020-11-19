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

use App\Fixture\Factory\PostExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class PostFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, PostExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

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
