<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\DealerPrice;

use App\Behat\Page\Backend\Crud\UpdatePage as BaseUpdatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class UpdatePage extends BaseUpdatePage
{
    /**
     * @param string $name
     */
    public function changeName($name)
    {
        $this->getElement('name')->setValue($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'name' => '#app_dealer_price_name',
        ]);
    }
}
