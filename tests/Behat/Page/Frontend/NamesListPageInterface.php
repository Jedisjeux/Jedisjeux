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

namespace App\Tests\Behat\Page\Frontend;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

/**
 * A page listing items, such as games or people, by their name.
 */
interface NamesListPageInterface extends SymfonyPageInterface
{
    /**
     * @return string[]
     */
    public function getNames(): array;
}
