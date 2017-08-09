<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Page\Backend\Person;

use AppBundle\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $firstName
     */
    public function specifyFirstName($firstName)
    {
        $this->getElement('first_name')->setValue($firstName);
    }

    /**
     * @param string $lastName
     */
    public function specifyLastName($lastName)
    {
        $this->getElement('last_name')->setValue($lastName);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements()
    {
        return array_merge(parent::getDefinedElements(), [
            'first_name' => '#person_fisrtName',
            'last_name' => '#person_lastName',
        ]);
    }
}
