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

use App\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $code
     *
     * @throws ElementNotFoundException
     */
    public function specifyCode($code)
    {
        $this->getElement('code')->setValue($code);
    }

    /**
     * @param string $name
     *
     * @throws ElementNotFoundException
     */
    public function specifyName($name)
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
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'code' => '#app_dealer_code',
            'image' => '#app_dealer_image',
            'name' => '#app_dealer_name',
        ]);
    }
}
