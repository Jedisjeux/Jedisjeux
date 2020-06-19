<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\FestivalList;

use App\Tests\Behat\Behaviour\DescribesIt;
use App\Tests\Behat\Behaviour\NamesIt;
use App\Tests\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    use NamesIt,
        DescribesIt;

    /**
     * @param string|null $startAt
     *
     * @throws ElementNotFoundException
     */
    public function specifyStartAt(?string $startAt)
    {
        $this->getElement('start_at')->setValue($startAt);
    }

    /**
     * @param string|null $endAt
     *
     * @throws ElementNotFoundException
     */
    public function specifyEndAt(?string $endAt)
    {
        $this->getElement('end_at')->setValue($endAt);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'description' => '#app_festival_list_description',
            'start_at' => '#app_festival_list_start_at',
            'end_at' => '#app_festival_list_end_at',
        ]);
    }
}
