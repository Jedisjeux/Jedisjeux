<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\AppBundle\Form\Type;

use AppBundle\Form\Type\ArticleContentType;
use Infinite\FormBundle\Form\Type\PolyCollectionType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContentTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(ArticleContentType::class, ['sylius']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArticleContentType::class);
    }

    function it_extends_abstract_resource_type()
    {
        $this->shouldHaveType(AbstractResourceType::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('app_article_content');
    }

    function it_builds_form(FormBuilderInterface $builder)
    {
        $builder->add('name', TextType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('title', TextType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('mainImage', 'app_imagine_block', Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('blocks', PolyCollectionType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('publishable', null, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('publishStartDate', DateTimeType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $builder->add('publishEndDate', DateTimeType::class, Argument::any())->shouldBeCalled()->willReturn($builder);
        $this->buildForm($builder, []);
    }
}
