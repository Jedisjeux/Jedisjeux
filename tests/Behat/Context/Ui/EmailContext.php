<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\EmailCheckerInterface;
use Monofony\Bundle\CoreBundle\Tests\Behat\Service\SharedStorageInterface;
use Webmozart\Assert\Assert;

final class EmailContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var EmailCheckerInterface
     */
    private $emailChecker;

    public function __construct(SharedStorageInterface $sharedStorage, EmailCheckerInterface $emailChecker)
    {
        $this->sharedStorage = $sharedStorage;
        $this->emailChecker = $emailChecker;
    }

    /**
     * @Then it should be sent to :recipient
     * @Then the email with reset token should be sent to :recipient
     * @Then the email with contact request should be sent to :recipient
     */
    public function anEmailShouldBeSentTo($recipient)
    {
        Assert::true($this->emailChecker->hasRecipient($recipient));
    }

    /**
     * @Then :count email(s) should be sent to :recipient
     */
    public function numberOfEmailsShouldBeSentTo($count, $recipient)
    {
        Assert::same($this->emailChecker->countMessagesTo($recipient), (int) $count);
    }

    /**
     * @Then a welcoming email should have been sent to :recipient
     */
    public function aWelcomingEmailShouldHaveBeenSentTo($recipient)
    {
        $this->assertEmailContainsMessageTo('Welcome to our store', $recipient);
    }

    /**
     * @param string $message
     * @param string $recipient
     */
    private function assertEmailContainsMessageTo($message, $recipient)
    {
        Assert::true($this->emailChecker->hasMessageTo($message, $recipient));
    }
}
