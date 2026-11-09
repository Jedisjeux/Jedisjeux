<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Twig\Context\ProductListContextFactory;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Index;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[ORM\Table(name: 'jdj_product_list_item')]
#[AsResource(
    alias: 'app.product_list_item',
    section: 'frontend',
    operations: [
        new Index(
            path: '/liste-de-jeux/{productListSlug}',
            template: 'frontend/product_list_item/index.html.twig',
            grid: 'app_frontend_product_list_item',
            twigContextFactory: ProductListContextFactory::class,
        ),
        new Index(
            shortName: 'account_index',
            routeName: 'app_frontend_account_product_list_item_index',
            path: '/mon-compte/liste-de-jeux/{productListSlug}',
            template: 'frontend/account/product_list_item/index.html.twig',
            grid: 'app_frontend_product_list_item',
            twigContextFactory: ProductListContextFactory::class,
        ),
        new Index(
            shortName: 'account_games_library',
            routeName: 'app_frontend_account_games_library',
            path: '/mon-compte/ma-ludotheque',
            template: 'frontend/account/games_library/index.html.twig',
            grid: 'app_frontend_game_library',
        ),
        new Index(
            shortName: 'user_games_library',
            routeName: 'app_frontend_user_games_library',
            path: '/utilisateurs/{username}/ludotheque',
            template: 'frontend/user/games_library/index.html.twig',
            grid: 'app_frontend_user_game_library',
        ),
    ],
)]
class ProductListItem implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var ProductList
     */
    #[ORM\ManyToOne(targetEntity: ProductList::class, inversedBy: 'items')]
    protected $list;

    /**
     * @var ProductInterface
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Product\Model\ProductInterface::class, inversedBy: 'listItems')]
    protected $product;

    public function getList(): ?ProductList
    {
        return $this->list;
    }

    public function setList(?ProductList $list): void
    {
        $this->list = $list;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }
}
