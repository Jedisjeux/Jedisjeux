<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Backend\Dealer;

use AppBundle\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $code
     */
    public function specifyCode($code)
    {
        $this->getElement('code')->setValue($code);
    }

    /**
     * @param string $name
     */
    public function specifyName($name)
    {
        $this->getElement('name')->setValue($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'code' => '#app_dealer_code',
            'name' => '#app_dealer_name',
        ]);
    }
}
