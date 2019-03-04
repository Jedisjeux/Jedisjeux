<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\Product;

use App\Behat\Behaviour\WorkflowActions;
use App\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UpdatePage extends BaseUpdatePage
{
    use WorkflowActions;

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
     * @return int
     *
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
            'name' => '#sylius_product_translations_en_US_name',
        ]);
    }
}
