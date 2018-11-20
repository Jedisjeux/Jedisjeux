<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\Person;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_person_index';
    }

    /**
     * @param string $fullName
     *
     * @return bool
     */
    public function isPersonOnList($fullName)
    {
        return null !== $this->getDocument()->find('css', sprintf('#people-list .lead:contains("%s")', $fullName));
    }
}
