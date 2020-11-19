<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Context\Ui\Frontend;

use App\Tests\Behat\Page\Frontend\Error\ErrorPage;
use App\Tests\Behat\Page\Frontend\HomePage;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class ErrorContext implements Context
{
    /**
     * @var ErrorPage
     */
    private $errorPage;

    /**
     * @var HomePage
     */
    private $homePage;

    public function __construct(ErrorPage $errorPage, HomePage $homePage)
    {
        $this->errorPage = $errorPage;
        $this->homePage = $homePage;
    }

    /**
     * @When I am on not found page
     */
    public function iAmOnNotFoundPage()
    {
        $this->errorPage->open(['code' => Response::HTTP_NOT_FOUND]);
    }

    /**
     * @Then I should see the title :title
     */
    public function iShouldSeeTheTitle(string $title)
    {
        Assert::eq($title, $this->errorPage->getTitle());
    }

    /**
     * @Then I can return to homepage
     */
    public function iCanReturnToHomepage()
    {
        $this->errorPage->getReturnToHomepageLink()->press();
        Assert::true($this->homePage->isOpen());
    }
}
