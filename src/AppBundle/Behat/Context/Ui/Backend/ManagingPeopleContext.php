<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Ui\Backend;

use AppBundle\Behat\Page\Backend\Person\CreatePersonPage;
use Behat\Behat\Context\Context;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ManagingPeopleContext implements Context
{
    /**x
     * @var CreatePersonPage
     */
    private $createPersonPage;

    /**
     * ManagingPeopleContext constructor.
     *
     * @param CreatePersonPage $createPersonPage
     */
    public function __construct(CreatePersonPage $createPersonPage)
    {
        $this->createPersonPage = $createPersonPage;
    }

    /**
     * @Given I want to create a new person
     */
    public function iWantToCreateANewPerson()
    {
        $this->createPersonPage->open();
    }
}
