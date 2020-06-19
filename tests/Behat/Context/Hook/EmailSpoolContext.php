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

namespace App\Tests\Behat\Context\Hook;

use App\Tests\Behat\Service\EmailCheckerInterface;
use Behat\Behat\Context\Context;
use Symfony\Component\Filesystem\Filesystem;

final class EmailSpoolContext implements Context
{
    /**
     * @var string
     */
    private $spoolDirectory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param EmailCheckerInterface $emailChecker
     * @param Filesystem $filesystem
     */
    public function __construct(EmailCheckerInterface $emailChecker, Filesystem $filesystem)
    {
        $this->spoolDirectory = $emailChecker->getSpoolDirectory();
        $this->filesystem = $filesystem;
    }

    /**
     * @BeforeScenario @email
     */
    public function purgeSpooledMessages()
    {
        $this->filesystem->remove($this->spoolDirectory);
    }
}
