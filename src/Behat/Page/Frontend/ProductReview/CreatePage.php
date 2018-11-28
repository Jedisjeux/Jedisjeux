<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Frontend\ProductReview;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;

class CreatePage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'sylius_frontend_product_review_create';
    }

    /**
     * @param string|null $title
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setTitle(?string $title)
    {
        $this->getElement('title')->setValue($title);
    }

    /**
     * @param string|null $comment
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function setComment(?string $comment)
    {
        try {
            $fieldId = $this->getElement('comment')->getAttribute('id');
            Assert::notEmpty($fieldId);
            $this->getSession()->executeScript("CKEDITOR.instances[\"$fieldId\"].setData(\"$comment\");");
        } catch (UnsupportedDriverActionException $exception) {
            $this->getElement('comment')->setValue($comment);
        }
    }

    /**
     * @param int $rate
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function rateReview(int $rate)
    {
        try {
            $this->getElement('rate', ['%rate%' => $rate])->click();
        } catch (\Exception $e) {
        }

    }

    /**
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function submit()
    {
        $this->getDocument()->pressButton('Create');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'title' => '#sylius_product_review_title',
            'comment' => '#sylius_product_review_comment',
            'rate' => '.rate-base-layer span:nth-child(%rate%) .fa-star',
        ]);
    }
}
