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
use AppBundle\Form\Type\ArticleType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(ArticleType::class, ['sylius']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArticleType::class);
    }

    function it_extends_abstract_resource_type()
    {
        $this->shouldHaveType(AbstractResourceType::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('app_article');
    }
}
