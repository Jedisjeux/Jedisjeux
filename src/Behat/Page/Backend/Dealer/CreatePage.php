<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\Dealer;

use App\Behat\Behaviour\NamesIt;
use App\Behat\Behaviour\SpecifiesItsCode;
use App\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    use SpecifiesItsCode,
        NamesIt;

    /**
     * @param string $path
     *
     * @throws ElementNotFoundException
     */
    public function attachImage(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $imageForm = $this->getElement('image');

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath.$path);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'image' => '#app_dealer_image',
        ]);
    }
}
