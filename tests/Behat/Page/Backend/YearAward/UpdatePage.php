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

namespace App\Tests\Behat\Page\Backend\YearAward;

use App\Tests\Behat\Bridge\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_year_award_update';
    }

    public function specifyYear(string $year): void
    {
        $this->getDocument()->fillField('Year', $year);
    }
}
