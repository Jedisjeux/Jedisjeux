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

namespace App\Tests\Behat\Page\Backend\Article;

class CreateForProductPage extends CreatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_article_create_for_product';
    }
}
