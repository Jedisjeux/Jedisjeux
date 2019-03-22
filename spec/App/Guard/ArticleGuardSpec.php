<?php

namespace spec\App\Guard;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArticleGuardSpec extends ObjectBehavior
{
    function let(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->beConstructedWith($authorizationChecker);
    }

    function it_returns_if_current_user_is_granted_to_ask_for_a_publication(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $authorizationChecker->isGranted('ROLE_REVIEWER')->willReturn(true);
        $this->canAskForPublication()->shouldReturn(true);

        $authorizationChecker->isGranted('ROLE_REVIEWER')->willReturn(false);
        $this->canAskForPublication()->shouldReturn(false);
    }

    function it_returns_if_current_user_is_granted_to_publish(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $authorizationChecker->isGranted('ROLE_PUBLISHER')->willReturn(true);
        $this->canPublish()->shouldReturn(true);

        $authorizationChecker->isGranted('ROLE_PUBLISHER')->willReturn(false);
        $this->canPublish()->shouldReturn(false);
    }
}
