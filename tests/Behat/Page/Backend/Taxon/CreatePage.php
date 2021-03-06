<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Taxon;

use App\Tests\Behat\Behaviour\NamesIt;
use App\Tests\Behat\Behaviour\SpecifiesItsCode;
use Behat\Mink\Exception\ElementNotFoundException;
use Monofony\Bridge\Behat\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    use NamesIt;
    use SpecifiesItsCode;

    public function getRouteName(): string
    {
        return 'sylius_backend_taxon_create';
    }

    /**
     * @throws ElementNotFoundException
     */
    public function specifySlug(?string $slug): void
    {
        $this->getElement('slug')->setValue($slug);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'slug' => '#sylius_taxon_translations_en_US_slug',
        ]);
    }
}
