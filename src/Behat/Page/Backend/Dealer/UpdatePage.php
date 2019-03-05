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

use App\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UpdatePage extends BaseUpdatePage
{
    /**
     * @param string $name
     *
     * @throws ElementNotFoundException
     */
    public function changeName($name)
    {
        $this->getElement('name')->setValue($name);
    }

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
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasOneImage(): bool
    {
        $image = $this->getElement('image');
        $item = $image->find('css', 'img');

        return null !== $item;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'image' => '#app_dealer_image',
            'name' => '#app_dealer_name',
        ]);
    }
}
