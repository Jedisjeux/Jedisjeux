<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[UniqueEntity(fields: ['list', 'product'], errorPath: 'product', message: 'app.festival_list_item.product.unique')]
#[ORM\Table('jdj_festival_list_item')]
#[AsResource(
    alias: 'app.festival_list_item',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin/festival-lists/{festivalListId}/items',
    vars: [
        'subheader' => 'app.ui.manage_festival_list_items',
        'route' => ['parameters' => ['festivalListId' => '$festivalListId']],
    ],
    operations: [
        new Index(
            path: '/',
            grid: 'app_backend_festival_list_item',
            vars: [
                'icon' => 'list',
                'festival_list' => "@=throw_not_found_on_null(sylius_repositories.get('app.repository.festival_list').find(request.attributes.get('festivalListId')))",
            ],
        ),
        new Create(
            path: '/new',
            factoryMethod: 'createForList',
            factoryArguments: ['list' => "@=throw_not_found_on_null(sylius_repositories.get('app.repository.festival_list').find(request.attributes.get('festivalListId')))"],
            redirectToRoute: 'app_backend_festival_list_item_index',
            redirectArguments: ['festivalListId' => 'resource.list.id'],
        ),
        new Update(
            path: '/{id}/edit',
            repositoryMethod: 'findOneBy',
            repositoryArguments: ['criteria' => self::ITEM_OF_LIST],
            redirectToRoute: 'app_backend_festival_list_item_index',
            redirectArguments: ['festivalListId' => 'resource.list.id'],
            vars: [
                'route' => ['parameters' => ['id' => '$id', 'festivalListId' => '$festivalListId']],
                'index' => ['route' => ['parameters' => ['festivalListId' => '$festivalListId']]],
            ],
        ),
        new Delete(
            path: '/{id}',
            repositoryMethod: 'findOneBy',
            repositoryArguments: ['criteria' => self::ITEM_OF_LIST],
            redirectArguments: ['festivalListId' => 'resource.list.id'],
        ),
    ],
)]
class FestivalListItem implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /** Criteria to find the item of the URL, only within the list of the URL */
    private const ITEM_OF_LIST = "@={'id': request.attributes.get('id'), 'list': request.attributes.get('festivalListId')}";

    /**
     * @var FestivalList
     */
    #[ORM\ManyToOne(targetEntity: FestivalList::class)]
    protected $list;

    /**
     * @var ProductInterface
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Product\Model\ProductInterface::class)]
    protected $product;

    public function getList(): ?FestivalList
    {
        return $this->list;
    }

    public function setList(?FestivalList $list): void
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
