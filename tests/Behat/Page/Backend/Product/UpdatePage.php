<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Product;

use App\Tests\Behat\Behaviour\NamesIt;
use App\Tests\Behat\Behaviour\WorkflowActions;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Monofony\Bridge\Behat\Crud\AbstractUpdatePage;
use Webmozart\Assert\Assert;

class UpdatePage extends AbstractUpdatePage
{
    use NamesIt;
    use WorkflowActions;

    public function getRouteName(): string
    {
        return 'sylius_admin_product_update';
    }

    /**
     * @throws ElementNotFoundException
     */
    public function attachImage(string $path): void
    {
        $this->clickTabIfItsNotActive('media');

        $filesPath = $this->getParameter('files_path');

        $this->getDocument()->clickLink('Add');

        $imageForm = $this->getLastImageElement();

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath.$path);
    }

    /**
     * @throws ElementNotFoundException
     */
    public function countImages(): int
    {
        $images = $this->getElement('images');
        $items = $images->findAll('css', 'div[data-form-collection="item"]');

        return count($items);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'images' => '#sylius_product_firstVariant_images',
        ]);
    }

    /**
     * @param string $tabName
     *
     * @throws ElementNotFoundException
     */
    private function clickTabIfItsNotActive($tabName)
    {
        $attributesTab = $this->getElement('tab', ['%name%' => $tabName]);
        if (!$attributesTab->hasClass('active')) {
            $attributesTab->click();
        }
    }

    /**
     * @throws ElementNotFoundException
     */
    private function getLastImageElement(): NodeElement
    {
        $images = $this->getElement('images');
        $items = $images->findAll('css', 'div[data-form-collection="item"]');

        Assert::notEmpty($items);

        return end($items);
    }
}
