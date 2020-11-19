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

use App\Fixture\Factory\ContactRequestExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ContactRequestFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, ContactRequestExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'contact_request';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
            ->scalarNode('first_name')->cannotBeEmpty()->end()
            ->scalarNode('last_name')->cannotBeEmpty()->end()
            ->scalarNode('email')->cannotBeEmpty()->end()
            ->scalarNode('body')->cannotBeEmpty()->end()
        ;
    }
}
