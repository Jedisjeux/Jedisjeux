<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\FestivalList;

use App\Behat\Behaviour\NamesIt;
use App\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    use NamesIt;

    /**
     * @param string $description
     */
    public function specifyDescription($description)
    {
        $this->getElement('description')->setValue($description);
    }

    /**
     * @param string $startAt
     */
    public function specifyStartAt($startAt)
    {
        $this->getElement('start_at')->setValue($startAt);
    }

    /**
     * @param string $endAt
     */
    public function specifyEndAt($endAt)
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
