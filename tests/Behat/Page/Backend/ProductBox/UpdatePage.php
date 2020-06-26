<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Backend\ProductBox;

use App\Tests\Behat\Behaviour\WorkflowActions;
use Behat\Mink\Exception\ElementNotFoundException;
use Monofony\Bundle\AdminBundle\Tests\Behat\Crud\AbstractUpdatePage;
use Webmozart\Assert\Assert;

class UpdatePage extends AbstractUpdatePage
{
    use WorkflowActions;

    public function getRouteName(): string
    {
        return 'app_backend_product_box_update';
    }

    /**
     * @param string $path
     *
     * @throws ElementNotFoundException
     */
    public function attachImage(string $path): void
    {
        $filesPath = $this->getParameter('files_path');

        $imageForm = $this->getElement('image');

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath.$path);
    }

    /**
     * @param int|null $height
     *
     * @throws ElementNotFoundException
     */
    public function changeHeight(?int $height)
    {
        $this->getElement('height')->setValue($height);
    }

    public function getImageFileError(): string
    {
        $element = $this->getDocument()->find('css', '#app_product_box_image .sylius-validation-error');
        Assert::notNull($element);

        return $element->getText();
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'height' => '#app_product_box_realHeight',
            'image' => '#app_product_box_image',
            'image_file' => '#app_product_box_image_file',
        ]);
    }
}
