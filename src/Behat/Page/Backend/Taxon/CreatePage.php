<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\Taxon;

use App\Behat\Behaviour\NamesIt;
use App\Behat\Behaviour\SpecifiesItsCode;
use App\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;
use Behat\Mink\Exception\ElementNotFoundException;

class CreatePage extends BaseCreatePage
{
    use NamesIt,
        SpecifiesItsCode;

    /**
     * @param string|null $slug
     *
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
