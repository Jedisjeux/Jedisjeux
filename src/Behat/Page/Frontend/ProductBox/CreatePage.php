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

use Behat\Mink\Exception\ElementNotFoundException;
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

    /**
     * @param int|null $height
     *
     * @throws ElementNotFoundException
     */
    public function specifyHeight(?int $height): void
    {
        $this->getElement('height')->setValue($height);
    }

    /**
     * @throws ElementNotFoundException
     */
    public function submit()
    {
        $this->getDocument()->pressButton('Create');
    }

    /**
     * {@inheritdoc}
     *
     * @throws ElementNotFoundException
     */
    public function checkValidationMessageFor($element, $message)
    {
        $errorLabel = $this->getElement($element)->getParent()->getParent()->find('css', '.form-error-message');

        if (null === $errorLabel) {
            throw new ElementNotFoundException($this->getSession(), 'Validation message', 'css', '.form-error-message');
        }

        return $message === $errorLabel->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'height' => '#app_product_box_realHeight',
        ]);
    }
}
