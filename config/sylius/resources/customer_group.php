<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Sylius\Component\Customer\Model\CustomerGroup;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Update;

// The model comes from Sylius: its operations cannot be declared with #[AsResource]
return (new ResourceMetadata())
    ->withClass(CustomerGroup::class)
    ->withAlias('sylius.customer_group')
    ->withSection('backend')
    ->withTemplatesDir('backend/crud')
    ->withRoutePrefix('/admin')
    ->withVars([
        'header' => 'sylius.ui.customer_groups',
        'subheader' => 'sylius.ui.manage_customer_groups',
    ])
    ->withOperations(new Operations([
        new Index(grid: 'sylius_backend_customer_group', vars: ['icon' => 'archive']),
        new Create(redirectToRoute: 'sylius_backend_customer_group_index'),
        new Update(redirectToRoute: 'sylius_backend_customer_group_index'),
        new Delete(),
    ]))
;
