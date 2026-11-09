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

use App\Tests\Behat\Bridge\Crud\AbstractIndexPage;

class IndexForProductPage extends AbstractIndexPage
{
    public function getRouteName(): string
    {
        return 'app_backend_article_per_product_index';
    }
}
