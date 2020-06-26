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
use Behat\Mink\Exception\ElementNotFoundException;
use Monofony\Bundle\AdminBundle\Tests\Behat\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    use NamesIt;
    use DescribesIt;

    public function getRouteName(): string
    {
        return 'app_backend_festival_list_create';
    }

    /**
     * @throws ElementNotFoundException
     */
    public function specifyStartAt(?string $startAt)
    {
        $this->getElement('start_at')->setValue($startAt);
    }

    /**
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
