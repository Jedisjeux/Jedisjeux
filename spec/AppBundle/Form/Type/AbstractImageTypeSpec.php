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

use AppBundle\Form\Type\AbstractImageType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class AbstractImageTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(AbstractImageType::class, ['sylius']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AbstractImageType::class);
    }

    function it_extends_abstract_resource_type()
    {
        $this->shouldHaveType(AbstractResourceType::class);
    }
}
