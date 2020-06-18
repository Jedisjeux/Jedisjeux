<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Behat\Page\Frontend\Product;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ShowPage extends SymfonyPage
{
    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'sylius_frontend_product_show';
    }

    /**
     * @return string
     *
     * @throws ElementNotFoundException
     */
    public function getName(): string
    {
        return $this->getElement('name')->getText();
    }

    /**
     * @return string
     *
     * @throws ElementNotFoundException
     */
    public function getBoxContent(): string
    {
        return $this->getElement('box_content')->getText();
    }

    /**
     * @return NodeElement[]
     *
     * @throws ElementNotFoundException
     */
    public function getMechanisms(): array
    {
        $mechanismsParagraph = $this->getElement('mechanisms');

        return $mechanismsParagraph->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws ElementNotFoundException
     */
    public function getThemes(): array
    {
        $mechanisms = $this->getElement('themes');

        return $mechanisms->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws ElementNotFoundException
     */
    public function getDesigners(): array
    {
        $designers = $this->getElement('designers');

        return $designers->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws ElementNotFoundException
     */
    public function getArtists(): array
    {
        $artists = $this->getElement('artists');

        return $artists->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws ElementNotFoundException
     */
    public function getPublishers(): array
    {
        $publishers = $this->getElement('publishers');

        return $publishers->findAll('css', 'a');
    }

    /**
     * @return array
     *
     * @throws ElementNotFoundException
     */
    public function getAwards(): array
    {
        $artists = $this->getElement('awards');

        return $artists->findAll('css', 'dd');
    }

    public function countReviews(): int
    {
        return count($this->getElement('reviews')->findAll('css', '.comment'));
    }

    public function countArticles(): int
    {
        return count($this->getElement('articles')->findAll('css', '.image-box'));
    }

    public function countGamePlays(): int
    {
        return count($this->getElement('game_plays')->findAll('css', '.image-box'));
    }

    public function countFiles(): int
    {
        return count($this->getElement('files')->findAll('css', '.comment'));
    }

    public function countVideos(): int
    {
        return count($this->getElement('videos')->findAll('css', '.overlay-container'));
    }

    /**
     * @param string $title
     *
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasReviewTitled(string $title): bool
    {
        return null !== $this->getElement('reviews')->find('css', sprintf('.comment:contains("%s")', $title));
    }

    /**
     * @param string $title
     *
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasArticleTitled(string $title): bool
    {
        return null !== $this->getElement('articles')->find('css', sprintf('.image-box .lead:contains("%s")', $title));
    }

    /**
     * @param string $name
     *
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasVariantNamed(string $name): bool
    {
        return null !== $this->getElement('variants')->find('css', sprintf('.image-box .lead:contains("%s")', $name));
    }

    /**
     * @param string $email
     *
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasGamePlayAddedByCustomerEmail(string $email): bool
    {
        return null !== $this->getElement('game_plays')->find('css', sprintf('.image-box h3:contains("%s")', $email));
    }

    /**
     * @param string $title
     *
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasFileTitled(string $title): bool
    {
        return null !== $this->getElement('files')->find('css', sprintf('.comment:contains("%s")', $title));
    }

    /**
     * @param string $title
     *
     * @return bool
     *
     * @throws ElementNotFoundException
     */
    public function hasVideoTitled(string $title): bool
    {
        return null !== $this->getElement('videos')->find('css', sprintf('.lead:contains("%s")', $title));
    }

    public function hasAssociation(string $productAssociationName): bool
    {
        return $this->hasElement('association', ['%association-name%' => $productAssociationName]);
    }

    public function hasProductInAssociation($productName, $productAssociationName)
    {
        $products = $this->getElement('association', ['%association-name%' => $productAssociationName]);

        Assert::notNull($products);

        return null !== $products->find('css', sprintf('.lead:contains("%s")', $productName));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'association' => '#sylius-product-association-%association-name%',
            'articles' => '#articles',
            'artists' => '#product-artists',
            'awards' => '#product-awards',
            'box_content' => '#box-content',
            'designers' => '#product-designers',
            'files' => '#files',
            'game_plays' => '#game-plays',
            'mechanisms' => '#product-mechanisms',
            'name' => 'h2.title',
            'publishers' => '#product-publishers',
            'reviews' => '#reviews .comments',
            'themes' => '#product-themes',
            'variants' => '#product-variants',
            'videos' => '#videos',
        ]);
    }
}
