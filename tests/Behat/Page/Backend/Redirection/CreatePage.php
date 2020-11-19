<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Redirection;

use Monofony\Bridge\Behat\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_redirection_create';
    }

    /**
     * @param string $source
     */
    public function specifySource($source)
    {
        $this->getElement('source')->setValue($source);
    }

    /**
     * @param string $destination
     */
    public function specifyDestination($destination)
    {
        $this->getElement('destination')->setValue($destination);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'source' => '#app_redirection_source',
            'destination' => '#app_redirection_destination',
        ]);
    }
}
