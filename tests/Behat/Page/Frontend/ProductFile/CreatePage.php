<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Page\Frontend\ProductFile;

use App\Tests\Behat\Behaviour\SpecifiesItsTitle;
use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class CreatePage extends SymfonyPage
{
    use SpecifiesItsTitle;

    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'app_frontend_product_file_create';
    }

    /**
     * @param string $path
     */
    public function attachFile(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $this
            ->getDocument()
            ->find('css', 'input[type="file"]')
            ->attachFile($filesPath.$path);
    }

    /**
     * @throws ElementNotFoundException
     */
    public function submit()
    {
        $this->getDocument()->pressButton('Create');
    }
}
