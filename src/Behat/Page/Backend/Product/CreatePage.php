<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Behat\Page\Backend\Product;

use App\Behat\Page\Backend\Crud\CreatePage as BaseCreatePage;
use App\Entity\GameAward;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CreatePage extends BaseCreatePage
{
    /**
     * @param string $name
     *
     * @throws ElementNotFoundException
     */
    public function specifyName($name)
    {
        $this->getElement('name')->setValue($name);
    }

    /**
     * @param string $slug
     *
     * @throws ElementNotFoundException
     */
    public function specifySlug($slug)
    {
        $this->getElement('slug')->setValue($slug);
    }

    /**
     * @param string $path
     *
     * @throws ElementNotFoundException
     */
    public function attachImage(string $path): void
    {
        $this->clickTabIfItsNotActive('media');

        $filesPath = $this->getParameter('files_path');

        $this->getDocument()->clickLink('Add');

        $imageForm = $this->getLastImageElement();

        $imageForm->find('css', 'input[type="file"]')->attachFile($filesPath . $path);
    }

    /**
     * @param string $path
     * @param string $title
     *
     * @throws ElementNotFoundException
     */
    public function addVideo(string $path, string $title)
    {
        $this->clickTabIfItsNotActive('media');

        $this->getDocument()->find('css', '#sylius_product_videos a[data-form-collection="add"]')->click();

        $this->getElement('video_title')->setValue($title);
        $this->getElement('video_path')->setValue($path);
    }

    /**
     * @param GameAward $gameAward
     * @param string $year
     *
     * @throws ElementNotFoundException
     */
    public function addAward(GameAward $gameAward, string $year)
    {
        $this->clickTabIfItsNotActive('awards');

        $this->getDocument()->find('css', '#sylius_product_yearAwards a[data-form-collection="add"]')->click();

        $this->getElement('award_name')->setValue($gameAward->getId());
        $this->getElement('award_year')->setValue($year);
    }

    /**
     * @param string $minPlayerCount
     */
    public function specifyMinPlayerCount($minPlayerCount)
    {
        $this->getElement('min_player_count')->setValue($minPlayerCount);
    }

    /**
     * @param string $maxPlayerCount
     */
    public function specifyMaxPlayerCount($maxPlayerCount)
    {
        $this->getElement('max_player_count')->setValue($maxPlayerCount);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'award_name' => '#sylius_product_yearAwards_0_award',
            'award_year' => '#sylius_product_yearAwards_0_year',
            'images' => '#sylius_product_firstVariant_images',
            'name' => '#sylius_product_translations_en_US_name',
            'min_player_count' => '#sylius_product_minPlayerCount',
            'max_player_count' => '#sylius_product_maxPlayerCount',
            'slug' => '#sylius_product_translations_en_US_slug',
            'tab' => '.menu [data-tab="%name%"]',
            'video_title' => '#sylius_product_videos_0_title',
            'video_path' => '#sylius_product_videos_0_path',
        ]);
    }

    /**
     * @param string $tabName
     *
     * @throws ElementNotFoundException
     */
    private function clickTabIfItsNotActive($tabName)
    {
        $attributesTab = $this->getElement('tab', ['%name%' => $tabName]);
        if (!$attributesTab->hasClass('active')) {
            $attributesTab->click();
        }
    }

    /**
     * @return NodeElement
     *
     * @throws ElementNotFoundException
     */
    private function getLastImageElement(): NodeElement
    {
        $images = $this->getElement('images');
        $items = $images->findAll('css', 'div[data-form-collection="item"]');

        Assert::notEmpty($items);

        return end($items);
    }
}
