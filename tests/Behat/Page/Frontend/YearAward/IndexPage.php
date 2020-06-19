<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\YearAward;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class IndexPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_year_award_index';
    }

    /**
     * @param string $title
     *
     * @return bool
     */
    public function isYearAwardOnList($title)
    {
        return null !== $this->getDocument()->find('css', sprintf('#year-award-list .lead:contains("%s")', $title));
    }
}
