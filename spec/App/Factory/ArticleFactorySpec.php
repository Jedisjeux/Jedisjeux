<?php

namespace spec\App\Factory;

use App\Context\CustomerContext;
use App\Entity\Article;
use App\Entity\Block;
use App\Factory\ArticleFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ArticleFactorySpec extends ObjectBehavior
{
    function let(RepositoryInterface $productRepository, CustomerContext $customerContext, FactoryInterface $blockFactory)
    {
        $this->beConstructedWith(Article::class, $productRepository, $customerContext, $blockFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArticleFactory::class);
    }

    function it_sets_author_with_logged_in_customer(CustomerContext $customerContext, CustomerInterface $customer)
    {
        $customerContext->getCustomer()->willReturn($customer);

        $article = $this->createNew();
        $article->getAuthor()->shouldReturn($customer);
    }

    function it_creates_an_article_for_product(ProductInterface $product): void
    {
        $article = $this->createForProduct($product);
        $article->getProduct()->shouldReturn($product);
    }

    function it_creates_a_review_article_for_product(ProductInterface $product, FactoryInterface $blockFactory, Block $block): void
    {
        $product->getName()->willReturn('Puerto Rico');
        $blockFactory->createNew()->willReturn($block);

        $article = $this->createReviewArticleForProduct($product);
        $article->getProduct()->shouldReturn($product);
    }
}
