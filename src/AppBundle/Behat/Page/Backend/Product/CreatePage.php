<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Backend\Product;

use AppBundle\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $name
     */
    public function specifyName($name)
    {
        $this->getElement('name')->setValue($name);
    }

    /**
     * @param string $slug
     */
    public function specifySlug($slug)
    {
        $this->getElement('slug')->setValue($slug);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'name' => '#sylius_product_translations_en_US_name',
            'slug' => '#sylius_product_translations_en_US_slug',
        ]);
    }
}
