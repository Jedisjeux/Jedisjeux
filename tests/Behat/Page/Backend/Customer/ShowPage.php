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

namespace App\Tests\Behat\Page\Backend\Customer;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class ShowPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'sylius_backend_customer_show';
    }

    public function hasContent(string $content): bool
    {
        return $this->getDocument()->hasContent($content);
    }
}
