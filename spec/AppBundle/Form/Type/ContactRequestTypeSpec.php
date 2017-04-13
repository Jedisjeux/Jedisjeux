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

use AppBundle\Form\Type\ContactRequestType;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\AbstractType;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ContactRequestTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContactRequestType::class);
    }

    function it_extends_abstract_resource_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('app_contact_request');
    }
}
