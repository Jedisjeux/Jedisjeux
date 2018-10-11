<?php

namespace spec\App\Entity;

use App\Entity\Redirection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Model\ResourceInterface;
use Zenstruck\RedirectBundle\Model\Redirect as BaseRedirect;

class RedirectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Redirection::class);
    }

    function it_implements_resource_interface()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_extends_a_redirect_model(): void
    {
        $this->shouldHaveType(BaseRedirect::class);
    }
}
