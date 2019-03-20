<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\ProductBox;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class CreatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_product_box_create';
    }

    /**
     * @param string $path
     */
    public function attachImage(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $this
            ->getDocument()
            ->find('css', 'input[type="file"]')
            ->attachFile($filesPath.$path);
    }
}
