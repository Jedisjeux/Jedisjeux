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

namespace App\Tests\Behat\Page\Backend\FestivalListItem;

use App\Tests\Behat\Bridge\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_festival_list_item_create';
    }

    /**
     * The game is chosen with an autocomplete, which stores its code in a hidden field
     */
    public function chooseProduct(string $code): void
    {
        $this->getElement('product')->setValue($code);
    }

    public function hasErrorMessage(string $message): bool
    {
        return $this->getDocument()->hasContent($message);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'product' => '#app_festival_list_item_product',
        ]);
    }
}
