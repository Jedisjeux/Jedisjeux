<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Redirection;

use Monofony\Bundle\AdminBundle\Tests\Behat\Crud\AbstractUpdatePage;

class UpdatePage extends AbstractUpdatePage
{
    public function getRouteName(): string
    {
        return 'app_backend_redirection_update';
    }

    /**
     * @param string $source
     */
    public function changeSource($source)
    {
        $this->getElement('source')->setValue($source);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'source' => '#app_redirection_source',
        ]);
    }
}
