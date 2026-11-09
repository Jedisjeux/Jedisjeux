<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Behat\Page\Backend\ProductAssociationType;

use App\Tests\Behat\Bridge\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'sylius_backend_product_association_type_update';
    }

    /**
     * The name is translated: it is filled in the default locale
     */
    public function nameIt(?string $name): void
    {
        $this->getElement('name')->setValue($name);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'name' => '#sylius_product_association_type_translations_en_US_name',
        ]);
    }
}
