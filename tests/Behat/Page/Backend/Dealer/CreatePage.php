<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\Dealer;

use App\Tests\Behat\Behaviour\NamesIt;
use App\Tests\Behat\Behaviour\SpecifiesItsCode;
use Behat\Mink\Exception\ElementNotFoundException;
use Monofony\Bridge\Behat\Crud\AbstractCreatePage;

class CreatePage extends AbstractCreatePage
{
    use SpecifiesItsCode;
    use NamesIt;

    public function getRouteName(): string
    {
        return 'app_backend_dealer_create';
    }

    /**
     * @throws ElementNotFoundException
     */
    public function attachImage(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $this->getElement('image_file')->attachFile($filesPath.$path);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'image' => '#app_dealer_image',
            'image_file' => '#app_dealer_image_file',
            'name' => '#app_dealer_name',
        ]);
    }
}
