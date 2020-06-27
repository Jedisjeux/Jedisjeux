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

use App\Fixture\Factory\ArticleReviewExampleFactory;
use App\Fixture\Factory\ExampleFactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ArticleReviewFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, ArticleReviewExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'article_review';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
            ->scalarNode('rating')->cannotBeEmpty()->end()
            ->scalarNode('comment')->cannotBeEmpty()->end()
            ->scalarNode('author')->cannotBeEmpty()->end()
            ->scalarNode('article')->cannotBeEmpty()->end()
        ;
    }
}
