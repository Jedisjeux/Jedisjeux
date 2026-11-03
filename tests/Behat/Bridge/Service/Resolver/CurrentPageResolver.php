<?php

declare(strict_types=1);

namespace App\Tests\Behat\Bridge\Service\Resolver;

use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Webmozart\Assert\Assert;

final class CurrentPageResolver implements CurrentPageResolverInterface
{
    public function __construct(private Session $session, private UrlMatcherInterface $urlMatcher)
    {
    }

    public function getCurrentPageWithForm(array $pages): SymfonyPageInterface
    {
        $routeParameters = $this->urlMatcher->match(parse_url($this->session->getCurrentUrl(), PHP_URL_PATH));
        Assert::allIsInstanceOf($pages, SymfonyPageInterface::class);

        foreach ($pages as $page) {
            if ($routeParameters['_route'] === $page->getRouteName()) {
                return $page;
            }
        }

        throw new \LogicException('Route name could not be matched to provided pages.');
    }
}
