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

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Replaces the FrameworkBundle ContainerAwareCommand removed in Symfony 5.
 */
abstract class ContainerAwareCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
