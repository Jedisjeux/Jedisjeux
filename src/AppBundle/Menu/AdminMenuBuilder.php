<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
final class AdminMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param FactoryInterface $factory
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return ItemInterface
     */
    public function createMenu()
    {
        $menu = $this->factory->createItem('root');

        $this->addCatalogSubMenu($menu);

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->addCustomersSubMenu($menu);
        }

        $this->addContentSubMenu($menu);

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->addForumSubMenu($menu);
            $this->addPartnershipSubMenu($menu);
            $this->addContactSubMenu($menu);
            $this->addErrorsSubMenu($menu);
            $this->addConfigurationSubMenu($menu);
        }

        return $menu;
    }

    public function addCatalogSubMenu(ItemInterface $menu)
    {
        $catalog = $menu
            ->addChild('catalog')
            ->setLabel('app.ui.catalog');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $catalog
                ->addChild('backend_taxon', ['route' => 'sylius_backend_taxon_index'])
                ->setLabel('sylius.ui.taxons')
                ->setLabelAttribute('icon', 'folder');
        }


        $catalog
            ->addChild('backend_product', ['route' => 'sylius_admin_product_index'])
            ->setLabel('sylius.ui.products')
            ->setLabelAttribute('icon', 'cube');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $catalog
                ->addChild('backend_association_type', ['route' => 'sylius_backend_product_association_type_index'])
                ->setLabel('sylius.ui.association_types')
                ->setLabelAttribute('icon', 'tasks');
        }

        $catalog
            ->addChild('backend_person', ['route' => 'app_backend_person_index'])
            ->setLabel('app.ui.people')
            ->setLabelAttribute('icon', 'spy');

        return $catalog;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    private function addCustomersSubMenu(ItemInterface $menu)
    {
        $customers = $menu
            ->addChild('customers')
            ->setLabel('sylius.ui.customer');

        $customers
            ->addChild('backend_customer', ['route' => 'sylius_backend_customer_index'])
            ->setLabel('sylius.ui.customers')
            ->setLabelAttribute('icon', 'users');

        $customers
            ->addChild('backend_customer_group', ['route' => 'sylius_backend_customer_group_index'])
            ->setLabel('sylius.ui.groups')
            ->setLabelAttribute('icon', 'archive');

        $customers
            ->addChild('backend_product_review', ['route' => 'sylius_backend_product_review_index'])
            ->setLabel('sylius.ui.product_reviews')
            ->setLabelAttribute('icon', 'star');

        $customers
            ->addChild('backend_game_play', ['route' => 'app_backend_game_play_index'])
            ->setLabel('app.ui.game_plays')
            ->setLabelAttribute('icon', 'play');

        $customers
            ->addChild('backend_product_list', ['route' => 'app_backend_product_list_index'])
            ->setLabel('app.ui.product_lists')
            ->setLabelAttribute('icon', 'list');

        return $customers;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    private function addContentSubMenu(ItemInterface $menu)
    {
        $content = $menu
            ->addChild('content')
            ->setLabel('app.ui.content');

        $content
            ->addChild('backend_article', ['route' => 'app_backend_article_index'])
            ->setLabel('app.ui.articles')
            ->setLabelAttribute('icon', 'newspaper');

//        $content
//            ->addChild('backend_static_content', ['route' => 'sylius_backend_static_content_index'])
//            ->setLabel('sylius.ui.static_contents')
//            ->setLabelAttribute('icon', 'file');

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $content
                ->addChild('backend_string_block', ['route' => 'app_backend_string_block_index'])
                ->setLabel('app.ui.string_blocks')
                ->setLabelAttribute('icon', 'font layout');
        }

        return $content;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    private function addForumSubMenu(ItemInterface $menu)
    {
        $forum = $menu
            ->addChild('forum')
            ->setLabel('app.ui.forum');

        $forum
            ->addChild('backend_topic', ['route' => 'app_backend_topic_index'])
            ->setLabel('app.ui.topics')
            ->setLabelAttribute('icon', 'comment');

        $forum
            ->addChild('backend_post', ['route' => 'app_backend_post_index'])
            ->setLabel('app.ui.posts')
            ->setLabelAttribute('icon', 'comments');

        return $forum;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    private function addPartnershipSubMenu(ItemInterface $menu)
    {
        $partnership = $menu
            ->addChild('partnership')
            ->setLabel('app.ui.partnership');

        $partnership
            ->addChild('backend_dealer', ['route' => 'app_backend_dealer_index'])
            ->setLabel('app.ui.dealers')
            ->setLabelAttribute('icon', 'building');

        $partnership
            ->addChild('backend_dealer_price', ['route' => 'app_backend_dealer_price_index'])
            ->setLabel('app.ui.prices')
            ->setLabelAttribute('icon', 'euro');

        return $partnership;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    private function addContactSubMenu(ItemInterface $menu)
    {
        $contact = $menu
            ->addChild('contact')
            ->setLabel('app.ui.contact');

        $contact
            ->addChild('backend_contact_request', ['route' => 'app_backend_contact_request_index'])
            ->setLabel('app.ui.contact_requests')
            ->setLabelAttribute('icon', 'mail');

        return $contact;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    private function addErrorsSubMenu(ItemInterface $menu)
    {
        $errors = $menu
            ->addChild('errors')
            ->setLabel('app.ui.errors');

        $errors
            ->addChild('backend_not_found_page', ['route' => 'app_backend_not_found_page_index'])
            ->setLabel('app.ui.not_found_pages')
            ->setLabelAttribute('icon', 'warning sign');

        return $errors;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return ItemInterface
     */
    private function addConfigurationSubMenu(ItemInterface $menu)
    {
        $configuration = $menu
            ->addChild('configuration')
            ->setLabel('sylius.ui.configuration');

        $configuration->addChild('backend_redirection', ['route' => 'app_backend_redirection_index'])
            ->setLabel('app.ui.redirections')
            ->setLabelAttribute('icon', 'exchange');

        return $configuration;
    }
}
