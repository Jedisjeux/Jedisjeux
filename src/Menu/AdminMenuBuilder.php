<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\AdminUi\Knp\Menu\MenuBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
final class AdminMenuBuilder implements MenuBuilderInterface
{
    public function __construct(
        private FactoryInterface $factory,
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function createMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu
            ->addChild('dashboard', ['route' => 'app_backend_dashboard'])
            ->setLabel('sylius.ui.dashboard')
            ->setLabelAttribute('icon', 'tabler:dashboard');

        if ($this->authorizationChecker->isGranted('ROLE_PRODUCT_MANAGER')) {
            $this->addCatalogSubMenu($menu);
        }

        if ($this->authorizationChecker->isGranted('ROLE_ARTICLE_MANAGER')) {
            $this->addContentSubMenu($menu);
        }

        if ($this->authorizationChecker->isGranted('ROLE_MODERATOR')) {
            $this->addModerationMenu($menu);
        }

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->addContactSubMenu($menu);
            $this->addConfigurationSubMenu($menu);
        }

        return $menu;
    }

    public function addCatalogSubMenu(ItemInterface $menu)
    {
        $catalog = $menu
            ->addChild('catalog')
            ->setLabel('app.ui.catalog')
            ->setLabelAttribute('icon', 'tabler:books')
            ->setExtra('always_open', true);

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $catalog
                ->addChild('backend_taxon', ['route' => 'sylius_backend_taxon_index'])
                ->setLabel('sylius.ui.taxons');
        }

        $catalog
            ->addChild('backend_product', ['route' => 'sylius_admin_product_index'])
            ->setLabel('sylius.ui.products');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $catalog
                ->addChild('backend_association_type', ['route' => 'sylius_backend_product_association_type_index'])
                ->setLabel('sylius.ui.association_types');
        }

        $catalog
            ->addChild('backend_person', ['route' => 'app_backend_person_index'])
            ->setLabel('app.ui.people');

        $catalog
            ->addChild('backend_game_award', ['route' => 'app_backend_game_award_index'])
            ->setLabel('app.ui.game_awards');

        return $catalog;
    }

    private function addModerationMenu(ItemInterface $menu)
    {
        $moderation = $menu
            ->addChild('moderation')
            ->setLabel('app.ui.moderation')
            ->setLabelAttribute('icon', 'tabler:shield-check');

        $moderation
            ->addChild('backend_customer', ['route' => 'sylius_backend_customer_index'])
            ->setLabel('sylius.ui.customers');

        $moderation
            ->addChild('backend_product_review', ['route' => 'sylius_backend_product_review_index'])
            ->setLabel('sylius.ui.product_reviews');

        $moderation
            ->addChild('backend_product_box', ['route' => 'app_backend_product_box_index'])
            ->setLabel('app.ui.product_boxes');

        $moderation
            ->addChild('backend_product_file', ['route' => 'app_backend_product_file_index'])
            ->setLabel('app.ui.product_files');

        $moderation
            ->addChild('backend_game_play', ['route' => 'app_backend_game_play_index'])
            ->setLabel('app.ui.game_plays');

        $moderation
            ->addChild('backend_product_list', ['route' => 'app_backend_product_list_index'])
            ->setLabel('app.ui.product_lists');

        $moderation
            ->addChild('backend_topic', ['route' => 'app_backend_topic_index'])
            ->setLabel('app.ui.topics');

        $moderation
            ->addChild('backend_post', ['route' => 'app_backend_post_index'])
            ->setLabel('app.ui.posts');

        return $moderation;
    }

    /**
     * @return ItemInterface
     */
    private function addContentSubMenu(ItemInterface $menu)
    {
        $content = $menu
            ->addChild('content')
            ->setLabel('app.ui.content')
            ->setLabelAttribute('icon', 'tabler:news')
            ->setExtra('always_open', true);

        $content
            ->addChild('backend_article', ['route' => 'app_backend_article_index'])
            ->setLabel('app.ui.articles');

        $content
            ->addChild('backend_festival_list', ['route' => 'app_backend_festival_list_index'])
            ->setLabel('app.ui.festival_lists');

        return $content;
    }

    /**
     * @return ItemInterface
     */
    private function addContactSubMenu(ItemInterface $menu)
    {
        $contact = $menu
            ->addChild('contact')
            ->setLabel('app.ui.contact')
            ->setLabelAttribute('icon', 'tabler:mail');

        $contact
            ->addChild('backend_contact_request', ['route' => 'app_backend_contact_request_index'])
            ->setLabel('app.ui.contact_requests');

        return $contact;
    }

    /**
     * @return ItemInterface
     */
    private function addConfigurationSubMenu(ItemInterface $menu)
    {
        $configuration = $menu
            ->addChild('configuration')
            ->setLabel('sylius.ui.configuration')
            ->setLabelAttribute('icon', 'tabler:settings');

        $configuration
            ->addChild('backend_dealer', ['route' => 'app_backend_dealer_index'])
            ->setLabel('app.ui.dealers');

        $configuration
            ->addChild('backend_dealer_price', ['route' => 'app_backend_dealer_price_index'])
            ->setLabel('app.ui.prices');

        $configuration
            ->addChild('backend_customer_group', ['route' => 'sylius_backend_customer_group_index'])
            ->setLabel('sylius.ui.groups');

        return $configuration;
    }
}
