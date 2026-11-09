<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Entity\Article;
use App\Entity\GamePlay;
use App\Entity\Post;
use App\Entity\Topic;
use App\Factory\TopicFactory;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TopicFactoryTest extends TestCase
{
    private CustomerContextInterface&Stub $customerContext;

    private TopicFactory $factory;

    protected function setUp(): void
    {
        $this->customerContext = $this->createStub(CustomerContextInterface::class);
        $postFactory = $this->createStub(FactoryInterface::class);
        $postFactory->method('createNew')->willReturnCallback(static fn (): Post => new Post());

        $this->factory = new TopicFactory(
            Topic::class,
            $this->customerContext,
            $this->createStub(RepositoryInterface::class),
            $postFactory,
        );
    }

    public function testItSetAuthorWithCurrentCustomer(): void
    {
        $customer = $this->createStub(CustomerInterface::class);
        $this->customerContext->method('getCustomer')->willReturn($customer);

        $this->assertSame($customer, $this->factory->createNew()->getAuthor());
    }

    public function testItCanCreateATopicForAGamePlay(): void
    {
        $gamePlay = new GamePlay();

        $topic = $this->factory->createForGamePlay($gamePlay);

        $this->assertSame($topic, $gamePlay->getTopic());
    }

    public function testItCanCreateATopicForAnArticle(): void
    {
        $article = new Article();

        $topic = $this->factory->createForArticle($article);

        $this->assertSame($topic, $article->getTopic());
    }

    public function testItCanCreateATopicForATaxon(): void
    {
        $taxon = $this->createStub(TaxonInterface::class);

        $this->assertSame($taxon, $this->factory->createForTaxon($taxon)->getMainTaxon());
    }
}
